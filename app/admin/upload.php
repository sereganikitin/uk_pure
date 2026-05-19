<?php
declare(strict_types=1);

final class UploadException extends RuntimeException {}

final class Upload
{
    /** Разрешённые MIME-типы и соответствующие расширения для изображений. */
    private const IMAGE_TYPES = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];

    private const DOC_TYPES = [
        'application/pdf'                                                            => 'pdf',
        'application/msword'                                                         => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'    => 'docx',
        'application/vnd.ms-excel'                                                   => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'          => 'xlsx',
        'application/zip'                                                            => 'zip',
        'text/plain'                                                                 => 'txt',
        'application/xml'                                                            => 'xml',
        'text/xml'                                                                   => 'xml',
        'image/jpeg'                                                                 => 'jpg',
        'image/png'                                                                  => 'png',
    ];

    /**
     * Принять загруженную картинку, по возможности ужать до 1600px по ширине,
     * сохранить в /uploads/news/. Возвращает имя файла (только basename).
     */
    public static function newsImage(array $file): string
    {
        self::ensureDir(UPLOADS . '/news');
        return self::handle($file, UPLOADS . '/news', self::IMAGE_TYPES, 8 * 1024 * 1024, true);
    }

    /** Принять документ, сохранить в /uploads/documents/. */
    public static function document(array $file): string
    {
        self::ensureDir(UPLOADS . '/documents');
        return self::handle($file, UPLOADS . '/documents', self::DOC_TYPES, 50 * 1024 * 1024, false);
    }

    /** Удалить файл по basename из заданной поддиректории uploads. */
    public static function deleteIn(string $subdir, ?string $basename): void
    {
        if (!$basename) return;
        $path = UPLOADS . '/' . trim($subdir, '/') . '/' . basename($basename);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private static function handle(array $file, string $dir, array $allowed, int $maxBytes, bool $isImage): string
    {
        if (!isset($file['tmp_name'], $file['error'])) {
            throw new UploadException('Неверная структура файла.');
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new UploadException('Ошибка загрузки файла (код ' . (int)$file['error'] . ').');
        }
        if (($file['size'] ?? 0) > $maxBytes) {
            throw new UploadException('Файл слишком большой (макс. ' . round($maxBytes / 1024 / 1024) . ' МБ).');
        }
        if (!is_uploaded_file($file['tmp_name'])) {
            throw new UploadException('Файл не получен сервером.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']) ?: '';
        if (!isset($allowed[$mime])) {
            throw new UploadException('Недопустимый тип файла: ' . $mime);
        }

        $ext = $allowed[$mime];
        $base = pathinfo($file['name'] ?? 'file', PATHINFO_FILENAME);
        $safe = preg_replace('/[^A-Za-z0-9_\-]+/', '_', admin_translit((string)$base));
        $safe = trim((string)$safe, '_-');
        if ($safe === '') $safe = 'file';
        $safe = mb_substr($safe, 0, 60);

        $name = $safe . '_' . date('Ymd') . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
        $target = $dir . '/' . $name;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            throw new UploadException('Не удалось сохранить файл.');
        }
        @chmod($target, 0644);

        if ($isImage) {
            self::tryResizeImage($target, 1600);
        }
        return $name;
    }

    /** Уменьшение по ширине через Imagick (если доступен), иначе оставляем как есть. */
    private static function tryResizeImage(string $path, int $maxWidth): void
    {
        if (!extension_loaded('imagick')) return;
        try {
            $im = new Imagick($path);
            $w = $im->getImageWidth();
            if ($w > $maxWidth) {
                $im->resizeImage($maxWidth, 0, Imagick::FILTER_LANCZOS, 1);
                $im->stripImage();
                $im->setImageCompressionQuality(85);
                $im->writeImage($path);
            }
            $im->clear();
            $im->destroy();
        } catch (\Throwable $e) {
            error_log('[upload-resize] ' . $e->getMessage());
        }
    }

    private static function ensureDir(string $dir): void
    {
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }
}
