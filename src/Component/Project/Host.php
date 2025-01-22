<?php namespace App\Component\Project;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Entity\ProjectHostOption;

class Host
{
    // Project Types
    const TYPE_LAMP             = 'Lamp';
    const TYPE_ASPNET_REVERSE   = 'DotNet';
    const TYPE_JSP              = 'JspRewrite';
    const TYPE_JSP_REVERSE      = 'Jsp';
    const TYPE_PYTHON           = 'Python';
    const TYPE_RUBY             = 'Ruby';
}
