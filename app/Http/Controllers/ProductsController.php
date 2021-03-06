<?php

namespace App\Http\Controllers;

use App\Exceptions\ProductNotFoundException;
use App\Http\Traits\ResponseTrait;
use App\Repositories\ProductRepository;
use App\Validators\ProductValidator;
use Dingo\Api\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class ProductsController
 * @package App\Http\Controllers
 */
class ProductsController extends BaseController
{
    use ResponseTrait;



    /**
     * @var ProductRepository
     */
    protected $repository;

    /**
     * ProductsController constructor.
     * @param ProductRepository $repository
     */
    public function __construct(ProductRepository $repository){
        $this->repository = $repository;
    }


    /**
     * Display a listing of products
     *
     * @return Response
     */
    public function index(Request $request)
    {

        if(!is_null($request->input('filter'))){ // skip presenter if filters is in query

            $products = $this->repository->skipPresenter(true)->paginate($request->input('limit'));

        }else{

            $products = $this->repository->paginate($request->input('limit'));
        }

        if(!is_array($products)) $products = $products->toArray();

        return $this->respondWithArray($products,'Products Listed');

    }

    /**
     * Get one product
     *
     * @param $id
     */
    public function show($id)
    {
        try{

            $product = $this->repository->find($id);

            return $this->respondWithItem($product,'Product Listed',Response::HTTP_OK);

        }catch (ProductNotFoundException $exception){

            return $this->failureResponse($exception->getMessage(),Response::HTTP_NOT_FOUND);

        }catch (ModelNotFoundException $exception){

            return $this->failureResponse('Product Not Found',Response::HTTP_NOT_FOUND);
        }




    }

    /**
     * Create a product
     *
     * @param \Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function store(ProductValidator $validator,Request $request)
    {

        $validator = \Validator::make($request->input(), $validator->getRules( ValidatorInterface::RULE_CREATE));

        if ($validator->fails()) {


            return $this->respondWithErrors($validator);

        }

        return $this->respondWithItem($this->repository->store($request->all()),'Product Created',Response::HTTP_CREATED);

    }

    /**
     * Change a product
     *
     * @param \Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function update($id,ProductValidator $validator,Request $request)
    {

        $validator = \Validator::make($request->input(), $validator->getRules( ValidatorInterface::RULE_UPDATE));

        if ($validator->fails()) {


            return $this->respondWithErrors($validator);

        }

        try{


            $product = $this->repository->updateOrCreate($request->all(),$request->query());


            return $this->respondWithItem($product,'Product Updated',Response::HTTP_OK);

        }catch (ModelNotFoundException $exception){

            return $this->failureResponse('Product Not Found',Response::HTTP_NOT_FOUND);
        }


    }

    /**
     * Update a product
     *
     * @param \Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function patch($id,ProductValidator $validator,Request $request)
    {

        $validator = \Validator::make($request->input(), $validator->getRules( ValidatorInterface::RULE_UPDATE));

        if ($validator->fails()) {


            return $this->respondWithErrors($validator);

        }

        try{


            $product = $this->repository->update($request->all(),$id);


            return $this->respondWithItem($product,'Product Updated',Response::HTTP_OK);

        }catch (ModelNotFoundException $exception){

            return $this->failureResponse('Product Not Found',Response::HTTP_NOT_FOUND);
        }


    }



    /**
     * Remove the specified product.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try{

            $this->repository->delete($id);

            return $this->successResponse(null,'Product Deleted',Response::HTTP_NO_CONTENT);


        }catch (ModelNotFoundException $exception){

            return $this->failureResponse('Product Not Found',Response::HTTP_NOT_FOUND);
        }
    }
}
