<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\OrderPdf\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Common\EccubeConfig;
use Plugin\OrderPdf\Entity\OrderPdf;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class OrderPdfType.
 */
class OrderPdfType extends AbstractType
{
    /** @var EccubeConfig */
    private $eccubeConfig;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * OrderPdfType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EccubeConfig $eccubeConfig, EntityManagerInterface $entityManager)
    {
        $this->eccubeConfig = $eccubeConfig;
        $this->entityManager = $entityManager;
    }

    /**
     * Build config type form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $config = $this->eccubeConfig;
        $builder
            ->add('ids', TextType::class, [
                'label' => 'admin.plugin.order_pdf.label.001',
                'required' => false,
                'attr' => ['readonly' => 'readonly'],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('issue_date', DateType::class, [
                'label' => 'admin.plugin.order_pdf.label.002',
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'required' => true,
                'data' => new \DateTime(),
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\DateTime(),
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'admin.plugin.order_pdf.label.003',
                'required' => false,
                'attr' => ['maxlength' => $config['eccube_stext_len']],
                'constraints' => [
                    new Assert\Length(['max' => $config['eccube_stext_len']]),
                ],
            ])
            // メッセージ
            ->add('message1', TextType::class, [
                'label' => 'admin.plugin.order_pdf.label.004',
                'required' => false,
                'attr' => ['maxlength' => $config['OrderPdf']['const']['order_pdf_message_len']],
                'constraints' => [
                    new Assert\Length(['max' => $config['OrderPdf']['const']['order_pdf_message_len']]),
                ],
                'trim' => false,
            ])
            ->add('message2', TextType::class, [
                'label' => 'admin.plugin.order_pdf.label.005',
                'required' => false,
                'attr' => ['maxlength' => $config['OrderPdf']['const']['order_pdf_message_len']],
                'constraints' => [
                    new Assert\Length(['max' => $config['OrderPdf']['const']['order_pdf_message_len']]),
                ],
                'trim' => false,
            ])
            ->add('message3', TextType::class, [
                'label' => 'admin.plugin.order_pdf.label.006',
                'required' => false,
                'attr' => ['maxlength' => $config['OrderPdf']['const']['order_pdf_message_len']],
                'constraints' => [
                    new Assert\Length(['max' => $config['OrderPdf']['const']['order_pdf_message_len']]),
                ],
                'trim' => false,
            ])
            // 備考
            ->add('note1', TextType::class, [
                'label' => 'admin.plugin.order_pdf.label.007',
                'required' => false,
                'attr' => ['maxlength' => $config['eccube_stext_len']],
                'constraints' => [
                    new Assert\Length(['max' => $config['eccube_stext_len']]),
                ],
            ])
            ->add('note2', TextType::class, [
                'label' => 'admin.plugin.order_pdf.label.008',
                'required' => false,
                'attr' => ['maxlength' => $config['eccube_stext_len']],
                'constraints' => [
                    new Assert\Length(['max' => $config['eccube_stext_len']]),
                ],
            ])
            ->add('note3', TextType::class, [
                'label' => 'admin.plugin.order_pdf.label.009',
                'required' => false,
                'attr' => ['maxlength' => $config['eccube_stext_len']],
                'constraints' => [
                    new Assert\Length(['max' => $config['eccube_stext_len']]),
                ],
            ])
            ->add('default', CheckboxType::class, [
                'required' => false,
                'label' => 'admin.plugin.order_pdf.label.010',
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $form->getData();
                if (!isset($data['ids']) || !is_string($data['ids'])) {
                    return;
                }
                $ids = explode(',', $data['ids']);

                $qb = $this->entityManager->createQueryBuilder();
                $qb->select('count(o.id)')
                    ->from('Eccube\\Entity\\Order', 'o')
                    ->where($qb->expr()->in('o.id', ':ids'))
                    ->setParameter('ids', $ids);
                $actual = $qb->getQuery()->getSingleScalarResult();
                $expected = count($ids);
                if ($actual != $expected) {
                    $form['ids']->addError(
                        new FormError(trans('admin.plugin.order_pdf.parameter.notfound'))
                    );
                }
            });
    }

    /**
     * Get name method (form factory name).
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_order_pdf';
    }
}
