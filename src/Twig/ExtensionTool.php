<?php namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ExtensionTool extends AbstractExtension
{
	
	public function getFunctions()
	{
		return array(
		    new TwigFunction( 'vs_tool_exists', array( $this, 'exists' ) ),
		);
	}
	
	public function exists( $tool )
	{
		return is_dir( APP_ROOT . '/dir/tools' . $tool['root_dir'] );
	}
}
