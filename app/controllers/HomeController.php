<?php
/** Simple landing page after login: links to the six processes (no charts). */
class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home/index', [
            'title' => 'Home',
            'user'  => Auth::user(),
            'tiles' => [
                // [process, label, path, functional?]
                ['1.0', 'Farmer/Fisherfolk Registry', '/farmers', true],
                ['2.0', 'Agricultural Resources', '/farms', false],
                ['3.0', 'GIS Mapping', '/map', false],
                ['4.0', 'Agricultural Monitoring', '/monitoring', false],
                ['5.0', 'Programs and Assistance', '/programs', true],
                ['6.0', 'Reports', '/reports', true],
            ],
        ]);
    }
}
