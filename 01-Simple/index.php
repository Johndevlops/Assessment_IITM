<?php
// index.php 20150101 - 20170302
// Copyright (C) 2015-2017 Mark Constable <markc@renta.net> (AGPL-3.0)
// Inline_Documentation by:  John Peter| ON: 07/06/2016

echo new class // Declaring the class
{
    private // Declaring data types
    $in = [
        'm'     => 'home',      // Storing the value of m in an array
    ],
    
    $out = [ // Storing values in an array
        'doc'   => 'SPE::01',
        'nav1'  => '',
        'head'  => 'Simple',
        'main'  => '<p>Error: missing page!</p>',
        'foot'  => 'Copyright (C) 2015-2017 Mark Constable (AGPL-3.0)',
    ],
    $nav1 = [ // Storing values in an array
        ['Home', '?m=home'],
        ['About', '?m=about'],
        ['Contact', '?m=contact'],
    ];

    public function __construct() // Main function
    {
        $this->in['m'] = $_REQUEST['m'] ?? $this->in['m']; // Request the values of m from array   
        if (method_exists($this, $this->in['m'])) // Check if value exist
            $this->out['main'] = $this->{$this->in['m']}(); // If value exist, set it as main, so that output will be home
        foreach ($this->out as $k => $v) // Loop through array out(array name) for loading entire page
            $this->out[$k] = method_exists($this, $k) ? $this->$k() : $v; // Assigning each as key value
    }

    public function __toString() : string // Invoking function html as a string
    {
        return $this->html();
    }

    private function nav1() : string // Loading the page navigation menu
    {
        return '
      <nav>' . join('', array_map(function ($n) { // Returning the values from array nav1(array name) & display it as page navigation menu
            return '
        <a href="' . $n[1] . '">' . $n[0] . '</a>';
        }, $this->nav1)) . '
      </nav>';
    }

    private function head() : string // Loading the header
    {
        return '
    <header>
      <h1>' . $this->out['head'] . '</h1>' . $this->out['nav1'] . '
    </header>';
    }

    private function main() : string // Loading the main body content
    {
        return '
    <main>' . $this->out['main'] . '
    </main>';
    }

    private function foot() : string  // Loading the footer
    {
        return '
    <footer>
      <p><em><small>' . $this->out['foot'] . '</small></em></p> 
    </footer>';
    }

    private function html() : string
    {
        extract($this->out, EXTR_SKIP); // importing all variables from array out(array name)
        return '<!DOCTYPE html>
            <html lang="en">
              <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>' . $doc . '</title>
              </head>
              <body>' . $head . $main . $foot . '
              </body>
            </html>
            ';
    }

    private function home() { return '<h2>Home Page</h2><p>Lorem ipsum home.</p>'; }
    private function about() { return '<h2>About Page</h2><p>Lorem ipsum about.</p>'; }
    private function contact() { return '<h2>Contact Page</h2><p>Lorem ipsum contact.</p>'; }
};
