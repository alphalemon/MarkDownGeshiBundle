<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\Block\MarkdownGeshiBundle\Core\ElFinder;

use AlphaLemon\ElFinderBundle\Core\Connector\AlphaLemonElFinderBaseConnector;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;

/**
 * Description of ElFinderMarkdownConnector
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class ElFinderMarkdownGeshiConnector extends AlphaLemonElFinderBaseConnector
{
    protected function configure()
    {
        $request = $this->container->get('request');
        
        $bundleFolder = AlToolkit::retrieveBundleWebFolder($this->container, 'AlphaLemonCmsBundle');
        $absoluteUploadPath = $bundleFolder . '/' . $this->container->getParameter('alcms.upload_assets_dir') . '/' . $this->container->getParameter('al.deploy_bundle.markdown_folder') ;
        
        $realUploadPath = $this->container->getParameter('kernel.root_dir') . '/../web/' . $absoluteUploadPath; 
        if(!is_dir($realUploadPath)) mkdir ($realUploadPath);
            
        $options = array(
            'roots' => array(
                array(
                    'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
                    'path'          => $absoluteUploadPath . '/',         // path to files (REQUIRED)
                    'URL'           => $request->getScheme().'://'.$request->getHttpHost() . '/' . $absolutePath, // URL to files (REQUIRED)
                    'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
                )
            )
        );
        
        return $options;
    }
}
