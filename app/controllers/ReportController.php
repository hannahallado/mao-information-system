<?php
/** Process 6.0 - Reports Center + one reusable report template. */
class ReportController extends Controller
{
    private function catalogue(): array
    {
        return require BASE_PATH . '/config/reports.php';
    }

    public function index(): void
    {
        $this->view('reports/index', [
            'title'       => 'Reports Center',
            'groups'      => $this->catalogue(),
            'breadcrumbs' => [['Home', '/home'], ['Reports', null]],
            'menu_active' => '/reports/group/farmer-resource',
        ]);
    }

    public function group(string $group): void
    {
        $cat = $this->catalogue();
        if (!isset($cat[$group])) {
            abort(404);
        }
        $this->view('reports/group', [
            'title'       => $cat[$group]['title'],
            'group'       => $cat[$group],
            'breadcrumbs' => [['Home', '/home'], ['Reports', '/reports'], [$cat[$group]['title'], null]],
            'menu_active' => '/reports/group/' . $group,
        ]);
    }

    /** One report. Replace mockup() with the reusable report-view template. */
    public function show(string $slug): void
    {
        foreach ($this->catalogue() as $gslug => $group) {
            if (isset($group['reports'][$slug])) {
                [$title, $functional] = $group['reports'][$slug];
                $this->mockup([
                    'title'       => $title,
                    'group'       => $group['title'],
                    'process'     => '6.0 Generate Reports',
                    'status'      => $functional ? 'functional' : 'mockup',
                    'menu_active' => '/reports/group/' . $gslug,
                    'todo'        => [
                        'Report title, reporting period, generated date and applicable filters',
                        'Readable table with column headings, totals and summaries',
                        'Print and Export to Excel (.xlsx) buttons',
                        'Print layout with headings and page breaks',
                        'Friendly message when no records match the filters',
                    ],
                ]);
                return;
            }
        }
        abort(404);
    }
}
