<?php

return [
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    
    Symfony\Bundle\MakerBundle\MakerBundle::class => ['dev' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    
    Sylius\Bundle\ResourceBundle\SyliusResourceBundle::class => ['all' => true],
    FOS\RestBundle\FOSRestBundle::class => ['all' => true],
    JMS\SerializerBundle\JMSSerializerBundle::class => ['all' => true],
    winzou\Bundle\StateMachineBundle\winzouStateMachineBundle::class => ['all' => true],
    Symfony\WebpackEncoreBundle\WebpackEncoreBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    
    //Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['all' => true],
    //Webonaute\DoctrineFixturesGeneratorBundle\DoctrineFixturesGeneratorBundle::class => ['all' => true],
    //App\VS\FixtureGenerateBundle\VsFixtureGenerateBundle::class => ['all' => true],
    
    // VS Application
    VS\ApplicationBundle\VSApplicationBundle::class => ['all' => true],
    Knp\Bundle\MenuBundle\KnpMenuBundle::class => ['all' => true],
    WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle::class => ['all' => true],
    Sylius\Bundle\ThemeBundle\SyliusThemeBundle::class => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    
    // VS Users
    VS\UsersBundle\VSUsersBundle::class => ['all' => true],
    Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle::class => ['all' => true],
    SymfonyCasts\Bundle\VerifyEmail\SymfonyCastsVerifyEmailBundle::class => ['all' => true],
    SymfonyCasts\Bundle\ResetPassword\SymfonyCastsResetPasswordBundle::class => ['all' => true],
    
    // VS CMS
    VS\CmsBundle\VSCmsBundle::class => ['all' => true],
    FOS\CKEditorBundle\FOSCKEditorBundle::class => ['all' => true],
    
    // Symfony Debugging and Profiling
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true],
    VasekPurchart\TracyBlueScreenBundle\TracyBlueScreenBundle::class => ['all' => true],
];
