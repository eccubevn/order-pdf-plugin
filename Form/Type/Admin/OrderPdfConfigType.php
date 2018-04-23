<?php
namespace Plugin\OrderPdf\Form\Type\Admin;

use Eccube\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class OrderPdfConfigType extends AbstractType
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * OrderPdfConfigType constructor.
     *
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('wkhtml_exec_path', 'text', array(
            'required' => false,
            'label' => 'Wkhtml execute path',
        ))->add('timeout', 'text', array(
            'required' => false,
            'label' => 'Timeout',
            'constraints' => array(
                new Assert\GreaterThanOrEqual(array(
                    'value' => 1,
                )),
                new Assert\Regex(array('pattern' => '/^\d+$/')),
            )
        ));
    }

    /**
     * {@inheritdoc}
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => 'Plugin\OrderPdf\Entity\OrderPdfConfig'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_order_pdf_config';
    }
}