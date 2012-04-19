<?php

namespace AlphaLemon\Block\MarkdownGeshiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MarkdownElFinderController extends Controller
{
    public function connectMarkdownAction()
    {
        $connector = $this->container->get('el_finder_markdown_connector');
        $connector->connect();
    }
}
