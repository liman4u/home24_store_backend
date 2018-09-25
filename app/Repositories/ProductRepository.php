<?php
/**
 * Created by PhpStorm.
 * User: limanadamu
 * Date: 25/09/2018
 * Time: 9:06 AM
 */

namespace App\Repositories;

use App\Models\Product;
use Prettus\Repository\Eloquent\BaseRepository;

class ProductRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Product::class;
    }

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name' => 'like',
        'price'
    ];



    /**
     * @return string
     */
    public function presenter()
    {
        return "App\\Presenters\\ProductPresenter";
    }

    /**
     * Inject Request Criteria for searches and filters
     */
    public function boot(){

        $this->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));

    }


    /**
     * Create product action
     *
     * @param array $inputs
     * @return mixed
     */
    public function store(array $inputs)
    {
        $this->skipPresenter(false);

        return parent::create($inputs);
    }

    /**
     * Update product action
     *
     * @param array $inputs
     * @param $id
     * @return mixed
     */
    public function update(array $inputs,$id)
    {

        $this->skipPresenter(false);

        return parent::update($inputs, $id);
    }

}