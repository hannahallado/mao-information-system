<?php
/** Dev-only style guide at /ui-kit: every component in the theme. Remove the route before final submission if you like. */
class StyleGuideController extends Controller
{
    public function index(): void
    {
        $this->view('styleguide/index', [
            'title'       => 'UI Kit',
            'breadcrumbs' => [['Home', '/home'], ['UI Kit', null]],
            'menu_active' => '/home',
        ]);
    }
}
