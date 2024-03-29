<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\Block\MarkdownGeshiBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;

use Highlight\Bundle\Providers\Providers;

use Symfony\Component\DependencyInjection\ContainerInterface;
use ThemesCore\TemplateSlots\AlSlot;

/**
 * Description of AlContentManagerMarkdown
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockManagerMarkdownGeshi extends AlBlockManager
{
    protected $provider = null;
    protected $hasHighlightedCode = false;


    public function __construct(ContainerInterface $container, AlSlot $slot = null)
    {
        parent::__construct($container, $slot);
        
        $kernel = $this->container->get('kernel');
        $params = array('config' => $this->container->getParameter('highlight'), 'cacheDir'=>$kernel->getCacheDir());
        $this->provider = new Providers(
            $params['config'],
            $params['cacheDir'],
            null
        );
    }
    
    /*
    public function getExternalStylesheet() {
        $stylesheets = parent::getExternalStylesheet();
        
        if($this->hasHighlightedCode) $stylesheets[] = '@HighlightBundle/Resources/public/*';
        
        return $stylesheets;
    }*/


    public function getDefaultValue()
    {
        $default = array(); 
        $default["HtmlContent"] = 'Click to load your markdown file';
        //$default["ExternalStylesheet"] = implode(',', $this->container->getParameter('al_markdown_required_stylesheets'));
        
        return $default;
    }
    
    public function getHideInEditMode()
    {
        return true;
    }
    
    protected function _doCodeBlocks_callback($matches)
    {
        if(!$this->hasHighlightedCode) $this->hasHighlightedCode = true;
        $params = explode(' ', $matches[2]);
        
        $this->provider->setNamedProvider($params[1]);
        $codeblock = $matches[3];
        $codeblock = preg_replace('/\A\n+|\n+\z/', '', $codeblock);
        
        return $this->provider->getHtml($codeblock, $params[0]);
    }
    
    public function getHtmlContent()
    {
        $bundleFolder = AlToolkit::retrieveBundleWebFolder($this->container, 'AlphaLemonCmsBundle');
        $absoluteUploadPath = $bundleFolder . '/' . $this->container->getParameter('alcms.upload_assets_dir');        
        $file = $this->container->getParameter('kernel.root_dir') . '/../web/' . $absoluteUploadPath . '/' . $this->alBlock->getHtmlContent(); 
        if(is_file($file))
        {
            $fileContents = file_get_contents($file);
            $fileContents = preg_replace('{\r\n?}', "\n", $fileContents);
            $fileContents = preg_replace_callback('{
				(?:\n\n|\A\n?)
				(	            # $1 = the code block -- one or more lines, starting with a space/tab
                                  \.\.code-block\s
                                    \[
                                      (             # $2 = provider and code
                                        .*?
                                      )
                                    \]\s*
				  (                 # $3 = the code block contents
                                    (?>
					[ ]{4}  # Lines must start with a tab or a tab-width of spaces
					.*\n+
				    )+
                                  )
				)
				((?=^[ ]{0,4}\S)|\Z)	# Lookahead for non-space at line-start, or end of doc
			}xm',
                array(&$this, '_doCodeBlocks_callback'), $fileContents);
            
            $content = $this->container->get('markdown.parser')->transform($fileContents);       
        }
        else
        {
            $content = $this->alBlock->getHtmlContent();
        }
        
        return $content;
    }
}
