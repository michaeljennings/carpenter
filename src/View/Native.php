<?php namespace Michaeljennings\Carpenter\View;

use Michaeljennings\Carpenter\Contracts\View;
use Michaeljennings\Carpenter\Exceptions\ViewNotFoundException;

class Native implements View {

    /**
     * Return the required view
     *
     * @param $view
     * @param array $data
     * @return string
     * @throws ViewNotFoundException
     */
    public function make($view, $data = array())
    {
        if ( ! file_exists($view)) {
            throw new ViewNotFoundException("The table template could not be found. No file found at '{$view}'");
        }

        extract($data);
        include($view);

        // Get the content
        $content = ob_get_contents();
        // Clear the output buffer
        ob_end_clean();
        // Return the content
        return $content;
    }

}