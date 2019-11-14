<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Validator;

class CategoryController extends Controller
{
    public function index()
    {      
        if(!parent::checkLogin()) 
        {
            return parent::response('Ha ocurrido un error con su sesión.', 301);
        }
        $user = parent::getUserFromToken();
        $user_id = $user->id;       
        if (self::isCategoriesEmpty($user_id)) 
        {  
            return parent::response('Aun no tienes categorias.', 400);
        }
        
        $categories = Category::where('user_id', $user_id)->get();
        
        $categoryNames = [];
        $categoryIDs = [];
        foreach ($categories as &$category) 
        {   
            array_push($categoryNames, $category->name);
            array_push($categoryIDs, $category->id);
        }
        return response()->json ([
                'categories' => $categoryNames,
                'IDs' => $categoryIDs
            ],200);
    }

    private function isCategoriesEmpty($userId)
    { 
        if(Category::where('user_id', $userId)->first()) 
        {
            return false;
        } else {
            return true; 
        }        
    }

    public function store(Request $request)
    {
        /*if(parent::checkLogin() == false) 
        {
            return response("No permissions", 301);
        } */      

        //$user = parent::getUserFromToken();
        $title = $_POST['title'];
        //$user_id = $user->id;

        if (Validator::isStringEmpty($title)) 
        {
            return parent::response('El nombre no puede quedar vacio.', 400);
        }

        if (!Validator::hasOnlyOneWord($title)) {
            return parent::response('El nombre debe contener una unica palabra.', 400);
        }
        
        if (Validator::exceedsMaxLength($title, 50)) {
            return parent::response('Nombre demasiado largo.', 400);
        }

        /*if (self::isCategoryNameInUse($title, $user->id)) 
        {
            return parent::response('Esta categoria ya existe.', 400);
        }*/
        
        $category = new category;
        $category->title = $title;
        $category->user_id = 1;
        $category->save();

        return parent::response('Categoria created', 201);
    }

    private function isCategoryNameInUse($name, $userId)
    {           
        $categories = Category::where('user_id', $userId)->get();
        foreach ($categories as &$category) 
        {
            if ($category->name == $name) 
            {
                return true; 
            }
        }
            
    }

    public function update(Request $request, $id)
    {      
        if(parent::checkLogin() == false) 
        {
            return parent::response('Ha ocurrido un problema con su sesión.', 301);
        }
        
        //creo una variable de la que se extrae el body de una petición POST/PUT/PATCH
        parse_str(file_get_contents("php://input"),$putData);   
        $name = $putData['name'];
        $user = parent::getUserFromToken();
        $user_id = $user->id;  
        
        if (Validator::isStringEmpty($name)) 
        {
            return parent::response('Los campos no pueden estar vacios', 400);
        }
        
        if (!Validator::hasOnlyOneWord($name)) {
            return parent::response('El nombre debe contener una unica palabra.', 400);
        }
        
        if (Validator::exceedsMaxLength($name, 50)) {
            return parent::response('Nombre demasiado largo.', 400);
        }      
        
        $category = Category::where('id',$id)->first();
        
        
        /*En caso de que el nombre introducido sea el mismo que el que se pretende cambiar no se tendrá en cuenta como categoría 
        repetida y se obviará */
        if (self::isCategoryNameInUse($name, $user->id) and $category->name != $name)
        {
            return parent::response('La categoría ya existe.', 400);     
        }

        $category->name = $name;
        $category->update();
        return parent::response('Categoria modificada.', 201);
        
    }

    public function destroy($id)
    {
        if(parent::checkLogin() == false) 
        {
            return parent::response('Ha ocurrido un problema con su sesión.', 301);
        }
        
        $category = Category::where('id',$id)->first();
        $category->delete();
        
        return parent::response('Categoria eliminada.', 201);        
    }
}
