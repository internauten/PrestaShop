<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace PrestaShopBundle\Form\Admin\Configure\ShopParameters\ProductPreferences;

use PrestaShop\PrestaShop\Adapter\Product\PaginationConfiguration;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class is responsible of managing the data manipulated using forms
 * in "Configure > Shop Parameters > Product Settings" page.
 */
class PaginationFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var PaginationConfiguration
     */
    private $configuration;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        PaginationConfiguration $configuration,
        TranslatorInterface $translator
    ) {
        $this->configuration = $configuration;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->configuration->getConfiguration();
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data)
    {
        if ($errors = $this->validate($data)) {
            return $errors;
        }

        return $this->configuration->updateConfiguration($data);
    }

    /**
     * Perform validation on form data before saving it.
     *
     * @param array $data
     *
     * @return array Returns array of errors
     */
    protected function validate(array $data)
    {
        $errors = [];
        $productsPerPage = $data['products_per_page'];
        if (!is_numeric($productsPerPage) || 0 > $productsPerPage) {
            $errors[] = [
                'key' => 'The %s field is invalid.',
                'domain' => 'Admin.Notifications.Error',
                'parameters' => [$this->translator->trans('Products per page', [], 'Admin.Shopparameters.Feature')],
            ];
        }

        return $errors;
    }
}
