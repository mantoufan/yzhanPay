<?php
namespace common\base;

class Trade extends Common
{
    public function getProduct($product, $app_id, $read_only = false)
    {
        $product['app_id'] = $app_id;
        return $this->getByParams('service\ProductService', $product, $read_only);
    }

    public function getPlan($plan, $app_id, $read_only = false)
    {
        $plan['app_id'] = $app_id;
        return $this->getByParams('service\PlanService', $plan, $read_only);
    }

    public function getCustomer($customer, $app_id, $read_only = false)
    {
        $customer['app_id'] = $app_id;
        return $this->getByParams('service\CustomerService', $customer, $read_only);
    }

    private function getByParams($service, $params, $read_only = false)
    {
        if (empty($params['id'])) {
            $id = $read_only ? null : $service::Create($params);
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
