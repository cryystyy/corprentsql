<?php

namespace HtmlCreator {

    function createHeader()
    {
        echo "<html><head><title>Etherium Beach</title>
        <link rel=\"icon\" type=\"image/ico\" href=\"images/icon.ico\" sizes=\"32x32\">
        <link rel=\"stylesheet\" href=\"style/mystyle.css\">
        <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css\" integrity=\"sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M\" crossorigin=\"anonymous\">
        <div class=\"container text-center\" style=\"height: 300px; width:1000px;\"><img class=\"text-center\" src=\"images/ethbc_logo.png\" width=\"320\" height=\"240\"><p><button type=\"button\" class=\"btn btn-primary float-right\" onclick=\"window.location.href='http://ethbc.space'\">Main Page</button></div></head><body background=\"images/background_nebula.jpg\" style=\" background-size:cover;\"><img alt = \"starfield overlay\" src=\"images/stars_and_lines.png\" class=\"starfield-overlay\">";
    }

    function createFooter()
    {
        echo "<script src=\"https://code.jquery.com/jquery-3.2.1.slim.min.js\" integrity=\"sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN\" crossorigin=\"anonymous\"></script>
        <script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js\" integrity=\"sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4\" crossorigin=\"anonymous\"></script>
        <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js\" integrity=\"sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1\" crossorigin=\"anonymous\"></script>
        </body></html>";
    }
}