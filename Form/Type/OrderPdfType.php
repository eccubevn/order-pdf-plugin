<?php
/*
 * This file is part of the Order Pdf plugin
 *
 * Copyright (C) 2016 LOCKON CO.,LTD. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\OrderPdf\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Eccube\Application;
use Eccube\Common\EccubeConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
    /** @var  EccubeConfig */
    private $eccubeConfig;

    /** @var  EntityManagerInterface */
    private $entityManager;

    /**
     * OrderPdfType constructor.
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
            ->add('ids', TextType::class, array(
                'label' => '注文番号',
                'required' => false,
                'attr' => array('readonly' => 'readonly'),
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            ))
            ->add('issue_date', DateType::class, array(
                'label' => '発行日',
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'required' => true,
                'data' => new \DateTime(),
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\DateTime(),
                ),
            ))
            ->add('title', TextType::class, array(
                'label' => '帳票タイトル',
                'required' => false,
                'attr' => array('maxlength' => $config['eccube_stext_len']),
                'constraints' => array(
                    new Assert\Length(array('max' => $config['eccube_stext_len'])),
                ),
            ))
            // メッセージ
            ->add('message1', TextType::class, array(
                'label' => '1行目',
                'required' => false,
                'attr' => array('maxlength' => $config['OrderPdf']['const']['order_pdf_message_len']),
                'constraints' => array(
                    new Assert\Length(array('max' => $config['OrderPdf']['const']['order_pdf_message_len'])),
                ),
                'trim' => false,
            ))
            ->add('message2', TextType::class, array(
                'label' => '2行目',
                'required' => false,
                'attr' => array('maxlength' => $config['OrderPdf']['const']['order_pdf_message_len']),
                'constraints' => array(
                    new Assert\Length(array('max' => $config['OrderPdf']['const']['order_pdf_message_len'])),
                ),
                'trim' => false,
            ))
            ->add('message3', TextType::class, array(
                'label' => '3行目',
                'required' => false,
                'attr' => array('maxlength' => $config['OrderPdf']['const']['order_pdf_message_len']),
                'constraints' => array(
                    new Assert\Length(array('max' => $config['OrderPdf']['const']['order_pdf_message_len'])),
                ),
                'trim' => false,
            ))
            // 備考
            ->add('note1', TextType::class, array(
                'label' => '1行目',
                'required' => false,
                'attr' => array('maxlength' => $config['eccube_stext_len']),
                'constraints' => array(
                    new Assert\Length(array('max' => $config['eccube_stext_len'])),
                ),
            ))
            ->add('note2', TextType::class, array(
                'label' => '2行目',
                'required' => false,
                'attr' => array('maxlength' => $config['eccube_stext_len']),
                'constraints' => array(
                    new Assert\Length(array('max' => $config['eccube_stext_len'])),
                ),
            ))
            ->add('note3', TextType::class, array(
                'label' => '3行目',
                'required' => false,
                'attr' => array('maxlength' => $config['eccube_stext_len']),
                'constraints' => array(
                    new Assert\Length(array('max' => $config['eccube_stext_len'])),
                ),
            ))
            ->add('default', CheckboxType::class, array(
                'required' => false,
                'label' => '入力内容を保存する',
            ))
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
