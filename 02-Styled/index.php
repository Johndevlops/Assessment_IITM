<?php
// index.php 20150101 - 20170302
// Copyright (C) 2015-2017 Mark Constable <markc@renta.net> (AGPL-3.0)

declare(strict_types = 1); // for function call

echo new class() extends Init // Inherits property from class Init
{
    protected
    $email = 'markc@renta.net',
    $in = [
        'm'     => 'home',  // Method [home|about|contact]
    ],
    $out = [
        'doc'   => 'SPE::02',
        'css'   => '',
        'nav1'  => '',
        'head'  => 'Styled',
        'main'  => 'Error: missing page!',
        'foot'  => 'Copyright (C) 2015 Mark Constable (AGPL-3.0)',
    ],
    $nav1 = [
        ['Home', '?m=home'],
        ['About', '?m=about'],
        ['Contact', '?m=contact'],
    ];
};

class Init
{
    public function __construct()
    {
        foreach ($this->in as $k => $v)
            $this->in[$k] = isset($_REQUEST[$k])
                ? htmlentities(trim($_REQUEST[$k])) : $v;

        if (method_exists($this, $this->in['m']))
            $this->out['main'] = $this->{$this->in['m']}();

        foreach ($this->out as $k => $v)
            $this->out[$k] = method_exists($this, $k) ? $this->$k() : $v;
    }

    public function __toString() : string
    {
        return $this->html();
    }

    private function css() : string // Defining styles
    {
        return '
    <link href="//fonts.googleapis.com/css?family=Roboto:100,300,400,500,300italic" rel="stylesheet" type="text/css">
    <style>
* { transition: 0.25s linear; }
body {
    background-color: #fff;
    color: #444;
    font-family: "Roboto", sans-serif;
    font-weight: 300;
    height: 50rem;
    line-height: 1.5;
    margin: 0 auto;
    max-width: 42rem;
}
h1, h2, h3, nav, footer {
    color: #0275d8;
    font-weight: 300;
    text-align: center;
    margin: 0.5rem 0;
}
nav a, .btn {
    background-color: #ffffff;
    border-radius: 0.2em;
    border: 0.01em solid #0275d8;
    display: inline-block;
    padding: 0.25em 1em;
    font-family: "Roboto", sans-serif;
    font-weight: 300;
    font-size: 1rem;
}
nav a:hover, button:hover, input[type="submit"]:hover, .btn:hover  {
    background-color: #0275d8;
    color: #fff;
    text-decoration: none;
}
label, input[type="text"], textarea, pre {
    display: inline-block;
    width: 100%;
    padding: 0.5em;
    font-size: 1rem;
    box-sizing : border-box;
}
p { margin-top: 0; }
a:link, a:visited { color: #0275d8; text-decoration: none; }
a:hover { text-decoration: underline; }
a.active { background-color: #2295f8; color: #ffffff; }
a.active:hover { background-color: #2295f8; }
.rhs { text-align: right; }
.center { text-align: center; }

@media (max-width: 46rem) { body { width: 92%; } }
        </style>';
    }

    private function nav1() : string // Loading navigation
    {
        $m = '?m='.$this->in['m'];
        return '
      <nav>' . join('', array_map(function ($n) use ($m) {
            $c = $m === $n[1] ? ' class="active"' : '';
            return '
        <a' . $c . ' href="' . $n[1] . '">' . $n[0] . '</a>';
        }, $this->nav1)) . '
      </nav>';
    }

    private function head() : string // Loading header
    {
        return '
    <header>
      <h1>' . $this->out['head'] . '</h1>' . $this->out['nav1'] . '
    </header>';
    }

    private function main() : string // Loading main page content
    {
        return '
    <main>' . $this->out['main'] . '
    </main>';
    }

    private function foot() : string // Loading page footer
    {
        return '
    <footer>
      <p><em><small>' . $this->out['foot'] . '</small></em></p>
    </footer>';
    }

    private function html() : string // Defining html contents
    {
        extract($this->out, EXTR_SKIP); // extracting array values of out(array name)
        return '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>' . $doc . '</title>' . $css . '
  </head>
  <body>' . $head . $main . $foot . '
  </body>
</html>
';
    }

    private function home() : string
    {
        $this->nav1 = array_merge($this->nav1, [ // merging values along with values in nav1(array name)
            ['Project Page', 'https://github.com/markc/spe/tree/master/02-Styled'],
            ['Issue Tracker', 'https://github.com/markc/spe/issues'],
        ]);
        return '
      <h2>Home</h2>
      <p>
This is an ultra simple single-file PHP7 framework and template system example.
Comments and pull requests are most welcome via the Issue Tracker link above.
      </p>';
    }

    private function about() : string // Defining about content
    {
        return '
      <h2>About</h2>
      <p>
This is an example of a simple PHP7 "framework" to provide the core
structure for further experimental development with both the framework
design and some of the new features of PHP7.
      </p>';
    }

    private function contact() : string // Defining contact section content
    {
        return '
      <h2>Email Contact Form</h2>
      <form id="contact-send" method="post" onsubmit="return mailform(this);">
        <p><input id="subject" required="" type="text" placeholder="Message Subject"></p>
        <p><textarea id="message" rows="9" required=""placeholder="Message Content"></textarea></p>
        <p class="rhs">
          <small>(Note: Doesn\'t seem to work with Firefox 50.1)</small>
          <input class="btn" type="submit" id="send" value="Send">
        </p>
      </form>
      <script>
      /* Mailing function */
function mailform(form) { 
    location.href = "mailto:' . $this->email . '" 
        + "?subject=" + encodeURIComponent(form.subject.value)
        + "&body=" + encodeURIComponent(form.message.value);
    form.subject.value = "";
    form.message.value = "";
    alert("Thank you for your message. We will get back to you as soon as possible.");
    return false;
}
      </script>';
    }
}
