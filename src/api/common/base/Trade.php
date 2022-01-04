<?php
namespace common\base;

class Trade extends Common
{
    public function getProducts($products, $global_data, $read_only = false)
    {
        $total_amount = 0;
        $subjects = array();
        $bodys = array();
        $products = array_map(function ($product) use ($global_data, $read_only, &$total_amount) {
            $total_amount += $product['amount'];
            $subjects[] = $prodcut['name'];
            $bodys[] = $prodcut['description'];
            $plan = $product['plan'];
            $customer = $product['customer'];
            unset($product['plan']);
            unset($product['customer']);
            $app_id = $global_data['app_id'];
            $currency = $global_data['currency'];
            $product['app_id'] = $app_id;
            $product = $this->getByParams('service\ProductService', $product, $read_only);
            $plan['app_id'] = $app_id;
            $plan['currency'] = $currency;
            $product['plan'] = $this->getByParams('service\PlanService', $plan, $read_only);
            $customer['app_id'] = $app_id;
            $product['customer'] = $this->getByParams('service\CustomerService', $customer, $read_only);
            return $product;
        }, $products);
        return array('subject' => mb_strimwidth(implode(',', $subjects), 0, 250, '...'), 'body' => mb_strimwidth(implode(',', $bodys), 0, 250, '...'), 'total_amount' => $total_amount, 'products' => $products);
    }

    private function getByParams($service, $params, $read_only = false)
    {
        if (empty($params['id'])) {
            $id = $read_only ? null : $service::Create(array('data' => $params));
            return array_merge($params, array('id' => $id));
        } else {
            $_id = $params['id'];
            if (count($params) !== 1) {
                unset($params['id']);
                $service::UpdateById($_id, $params);
            }
            return array_merge($service::GetById($_id), $params);
        }
    }
}
