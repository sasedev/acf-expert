<?php
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{

    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\AopBundle\JMSAopBundle(),
            new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Liuggio\ExcelBundle\LiuggioExcelBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),

            new Sasedev\Form\EntityidBundle\SasedevFormEntityidBundle(),
            new Sasedev\Doctrine\PostgresqlBundle\SasedevDoctrinePostgresqlBundle(),
            new Sasedev\Commons\SharedBundle\SasedevCommonsSharedBundle(),
            new Sasedev\Commons\BootstrapBundle\SasedevCommonsBootstrapBundle(),
            new Sasedev\Commons\TwigBundle\SasedevCommonsTwigBundle(),
            new Sasedev\ExtraToolsBundle\SasedevExtraToolsBundle(),
            new TFox\MpdfPortBundle\TFoxMpdfPortBundle(),

            new Acf\ResBundle\AcfResBundle(),
            new Acf\DataBundle\AcfDataBundle(),
            new Acf\SecurityBundle\AcfSecurityBundle(),
            new Acf\FrontBundle\AcfFrontBundle(),
            new Acf\AdminBundle\AcfAdminBundle(),
            new Acf\ClientBundle\AcfClientBundle(),
            new Acf\InfoBundle\AcfInfoBundle(),
            new Acf\PayrollBundle\AcfPayrollBundle(),
            new Acf\LiasseBundle\AcfLiasseBundle(),
            new Acf\ToolsBundle\AcfToolsBundle(),
            new Ao\FrontBundle\AoFrontBundle(),
            new Ao\AdminBundle\AoAdminBundle()
        ];

        if (in_array($this->getEnvironment(), [
            'dev',
            'test'
        ], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();

            if ('dev' === $this->getEnvironment()) {
                $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
                $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
            }
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__) . '/var/cache/' . $this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__) . '/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
