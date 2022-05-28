<?php

namespace App\View\Components;

use Illuminate\Http\Request;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public $links;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->links = [
            [
                "url" => "/",
                "label" => "Home",
                "icon" => "bi-house", // see https://icons.getbootstrap.com/
            ],
            [
                "url" => "/jobs",
                "label" => "Pekerjaan",
                "icon" => "bi-briefcase",
            ],
            [
                "url" => "/projects",
                "label" => "Proyek",
                "icon" => "bi-clipboard",
            ],
            [
                "url" => "/setting",
                "label" => "Pengaturan",
                "icon" => "bi-gear",
            ],
        ];

        for ($i = 0; $i < count($this->links); $i++) {
            $link = $this->links[$i];
            // Add active status for every matched URL Prefix
            $this->links[$i]['active'] = preg_match('$^' . $link['url'] . '$', request()->getPathInfo());
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.sidebar');
    }
}
