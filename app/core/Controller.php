<?php
/**
 * Base controller: view rendering, redirects, JSON and input helpers.
 */
class Controller
{
    /** Render a view inside a layout and return the HTML. */
    public static function render(string $view, array $data = [], ?string $layout = 'main'): string
    {
        $file = APP_PATH . '/views/' . $view . '.php';
        if (!is_file($file)) {
            throw new RuntimeException("View not found: $view");
        }

        load_old();
        load_errors();
        $data += ['title' => config('app_name')];

        $renderPart = static function (string $__file, array $__data): string {
            extract($__data, EXTR_SKIP);
            ob_start();
            require $__file;
            return (string) ob_get_clean();
        };

        $content = $renderPart($file, $data);
        if ($layout === null) {
            return $content;
        }
        return $renderPart(APP_PATH . "/views/layouts/$layout.php", $data + ['content' => $content]);
    }

    protected function view(string $view, array $data = [], ?string $layout = 'main'): void
    {
        echo self::render($view, $data, $layout);
    }

    /**
     * Placeholder screen for stubs: shows the title, process, status and the
     * checklist items still to build. Replace calls to this with real views.
     *   $page: title, group, process, status ('functional'|'mockup'), todo[], id?
     */
    protected function mockup(array $page): void
    {
        $page += ['status' => 'mockup', 'todo' => [], 'group' => '', 'process' => ''];
        $crumbs = [['Home', '/home']];
        if ($page['group'] !== '') {
            $crumbs[] = [$page['group'], null];
        }
        $crumbs[] = [$page['title'], null];
        $this->view('placeholder', $page + ['breadcrumbs' => $crumbs]);
    }

    protected function redirect(string $path): never
    {
        redirect($path);
    }

    protected function json(mixed $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /** POST value first, then GET; strings are trimmed. */
    protected function input(string $key, mixed $default = null): mixed
    {
        $value = $_POST[$key] ?? $_GET[$key] ?? $default;
        return is_string($value) ? trim($value) : $value;
    }

    protected function only(array $keys): array
    {
        $out = [];
        foreach ($keys as $key) {
            $out[$key] = $this->input($key);
        }
        return $out;
    }

    /** Validate input; on failure flash errors + old input and go back. */
    protected function validate(array $data, array $rules, string $backTo, array $labels = []): array
    {
        $v = Validator::make($data, $rules, $labels);
        if ($v->fails()) {
            flash_errors($v->errors());
            flash_old($data);
            redirect($backTo);
        }
        return $v->validated();
    }

    /** Technicians may only change records in their own sector. */
    protected function requireSector(string $sector): void
    {
        if (!Auth::canEditSector($sector)) {
            abort(403, 'This record belongs to another sector.');
        }
    }

    protected function isAjax(): bool
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }
}
