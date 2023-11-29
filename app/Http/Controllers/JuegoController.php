<?php

namespace App\Http\Controllers;

use App\Helpers\GameTimer;
use App\Models\Juego;
use Illuminate\Http\Request;



 /**
* @OA\Info(
*             title="API Toros y Vacas", 
*             version="0.9 beta",
*             description="Toros y vacas es un juego de lógica y estrategia"
* )
*
* @OA\Server(url="http://localhost/api_laravel/public/")
*/

class JuegoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Crear un nuevo Juego
     * @OA\Post (
     *     path="/game/newgame",
     *     tags={"Game"},
     *      @OA\Parameter(
     *          name="usuario",
     *          in="query",
     *          description = "Nombre del usuario del juego",
     *          example = "Raul",
     *          required=true,
     *          @OA\schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="edad",
     *          in="query",
     *          description = "edad del usuario del juego",
     *          example = "15",
     *          required=true,
     *          @OA\schema(type="number")
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="Juego creado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id_juego",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="inicio_juego",
     *                         type="string",
     *                         example="2023-11-28T15:46:17.467752Z"
     *                     ),
     *                     @OA\Property(
     *                         property="tiempo_juego",
     *                         type="number",
     *                         example="800"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         example="Juego creado satisfactoriamente.Su id de juego es :8), El contador de juego comienza en 2023-11-28 15:46:17 y tiene un tiempo de 800 antes de perder la partida."
     *                     ),
     *                    
     *                 )
     *             )
     *         )
     * ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de Valiacion",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         example="Error de validacion"
     *                     ),
     *                     @OA\Property(
     *                         property="error",
     *                         type="string",
     *                         example="El campo X de ser..."
     *                     ),
     *                     
     *                 )
     *             )
     *         )
     *     )                                     
     * )
     * )
     */
    public function newgame(Request $request)
    {
        $rules = ['usuario'=>'required|string|min:1|max:25',
                  'edad'=>'required|integer|min:8|max:120',  
                ];
        $validator =\Validator::make($request->input(),$rules);
        if($validator->fails())
        {
            return response()->json([
                'status'=>'Error de Validacion',
                'errors'=>$validator->errors()->all(),

            ],422);
        }
        $juego = new Juego($request->input());
        $juego->combinacion_real = $this->generar_combinacion_inicial();
        $juego->save();
        $timer= new GameTimer();
        $inicio = $timer->comenzar_juego();
        $timer->juego($juego->id);
        $tiempo = config('custom.udv');
        

        return response()->json([
            'status'=>true,
            'id_juego'=> $timer->get_juego(),
            'inicio_juego'=>$inicio,
            'tiempo_juego'=>$tiempo,
           // 'combinancion'=>$juego->combinacion_real,
            
            'message' =>'Juego creado satisfactoriamente. Su id de juego es :'.$juego->id.'), El contador de juego comienza en '.$inicio.' y tiene un tiempo de '.$tiempo.' antes de perder la partida.'
        ],201);
    }
    public function index()
    {
        $juegos = Juego::all();
        return response()->json($juegos);
    }
    /**
     * Display a listing of the resource.
     */
   /**
     * Proponer una combinacion de numeros para ser evaluada
     * @OA\Post (
     *     path="/game/combinacion",
     *     tags={"Game"},
     *      @OA\Parameter(
     *          name="combinacion_actual",
     *          in="query",
     *          description = "Combinacion de 4 digitos numericos",
     *          example = "2445",
     *          required=true,
     *          @OA\schema(type="number")
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="Combinacion evaluada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id_juego",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="inicio_juego",
     *                         type="string",
     *                         example="2023-11-28T15:46:17.467752Z"
     *                     ),
     *                     @OA\Property(
     *                         property="tiempo_juego",
     *                         type="number",
     *                         example="800"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         example="Juego creado satisfactoriamente.Su id de juego es :8), El contador de juego comienza en 2023-11-28 15:46:17 y tiene un tiempo de 800 antes de perder la partida."
     *                     ),
     *                    
     *                 )
     *             )
     *         )
     * ),
     *     @OA\Response(
     *         response=428,
     *         description="Error de Valiacion",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         example="Error de validacion"
     *                     ),
     *                     @OA\Property(
     *                         property="error",
     *                         type="string",
     *                         example="El campo X de ser..."
     *                     ),
     *                     
     *                 )
     *             )
     *         )
     *     )                                     
     * )
     *     @OA\Response(
     *         response=404,
     *         description="El Juego no existe",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="status",
     *                         type="string",
     *                     ),
     *                     @OA\Property(
     *                         property="mensaje",
     *                         type="string",
     *                     ),
     *                     
     *                 )
     *             )
     *         )
     *     )                                     
     * )
     *     @OA\Response(
     *         response=200,
     *         description="Tiempo de juego agotado",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="status",
     *                         type="string",
     *                     ),
     *                     @OA\Property(
     *                         property="mensaje",
     *                         type="string",
     *                     ),
     *                     @OA\Property(
     *                         property="combinacion_real",
     *                         type="string",
     *                     ),
     *                     
     *                 )
     *             )
     *         )
     *     )                                     
     * )
     * )
     */
  
    public function proponercombinacion(Request $request)
    {
        $rules = ['combinacion_actual'=>'required|integer|max:9999'];
        $validator =\Validator::make($request->input(),$rules);
        if($validator->fails())
        {
            return response()->json([
                'status'=>"error_validacion",
                'errors'=>$validator->errors()->all(),

            ],428);
        }
        $timer= new GameTimer();
         $juego = Juego::find($timer->get_juego());
         if(!$juego|| $juego->status!=1)
         {
            return response()->json([
                'status'=>"juego_no_encontrado",
                'message'=>'El juego no existe.',
                
            ],404);  
         }

         if($this->game_over($timer))
         {
            return response()->json([
                'status'=>"game_over",
                'message'=>'El tiempo máximo del juego fue alcanzado.',
                'combinacion_real'=>$juego->combinacion_real,
    
            ],200); 
         }
         if($juego->combinaciones!=NULL&&($this->combiacioncomprobada($request->actual,$juego->combinaciones))==false)
         {
            return response()->json([
                
                'status'=>"combinacion_jugada",
                'message'=>'Esta combinacion ya ha sido jugada.',
              //  'intento'=>$int,
    
            ],200);  
         }
        $int = $juego->intento;
        $result = $this->evaluar_combinacion($juego->combinacion_real,$request->combinacion_actual);
        $int++;
        $juego->intento = $int;
        $juego->combinaciones .= '-'.$request->combinacion_actual;
        $juego->combinacion_actual = $request->combinacion_actual;
        if($result===true)
        {
            $juego->status = 2;
            $juego->save();
            return response()->json([
                
                'status'=>"juego_ganado",
                'combinacion'=>$request->combinacion_actual,
                'resultado'=>$result,
                'intento'=>$int,
                'tiempo_restante'=>config('custom.udv') - $timer->ver_tiempo(),
                'evaluar_juego'=> $this->evaluar_juego($juego,$timer),
                'ranking'=>'ranking'
                
            ],200);

        }else{

            $juego->save();
            return response()->json([
                'status'=>"en_juego",
                'combinacion'=>$request->combinacion_actual,
                'resultado'=>$result,
                'intento'=>$int,
                'tiempo_restante'=>config('custom.udv') - $timer->ver_tiempo(),
                'evaluar_juego'=> $this->evaluar_juego($juego,$timer),
                'ranking'=>'ranking'
                
            ],200);

            }
        
    }

    public function evaluar_combinacion($real,$propuesta)
    {
      $toro = 0;
      $vaca = 0;
      $valores_real = array_map('intval',str_split($real));
      $valores_pro = array_map('intval',str_split($propuesta));
      foreach ($valores_real as $keyvr => $vr) 
      {
        foreach ($valores_pro as $key => $vp) 
        {
         $vr==$vp?($keyvr==$key?$toro++:$vaca++):null;
       }
      }
      if($toro==4)
      {
        true;
      }
      return $toro==4?TRUE:('V'.$vaca.'T'.$toro);  
    }
    public function combiacioncomprobada($actual,$combinaciones)
    {
        return strpos($combinaciones,$actual);
        
    }

     /**
     * Display a listing of the resource.
     */
   /**
     * Eliminar datos del juego
     * @OA\Post (
     *     path="/game/eliminar_juego",
     *     tags={"Game"},
     *      @OA\Parameter(
     *          name="id",
     *          in="query",
     *          description = "Identificador del juego a eliminar",
     *          example = "3",
     *          required=true,
     *          @OA\schema(type="number")
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Juego eliminaddo correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                    
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         example="Los datos del juego se han eliminado correctamente."
     *                     ),
     *                    
     *                 )
     *             )
     *         )
     * ),
     *   
     *     @OA\Response(
     *         response=404,
     *         description="El Juego no existe",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                  
     *                     @OA\Property(
     *                         property="mensaje",
     *                         type="El juego x no existe",
     *                     ),
     *                     
     *                 )
     *             )
     *         )
     *     )                                     
     * )
     * 
     * )
     */
  
    public function eliminar_game(Request $request)
    {
        $rules = ['id'=>'required|integer|max:9999'];
        $validator =\Validator::make($request->input(),$rules);
        if($validator->fails())
        {
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()->all(),

            ],400);
        }
        $juego = Juego::find($request->id);
        if($juego && $juego->status == 1)
        {
          $juego->status =0;
          if($juego->save())
          {
            return response()->json([
                'status'=>true,
                'message'=>'Juego eliminado satisfactoriamente',

            ],200);
          } else{
            return response()->json([
                'status'=>false,
                'message'=>'Error al eliminar el juego',

            ],400);
          } 
        }else{
            return response()->json([
                'status'=>true,
                'message'=>'Este juego no existe',

            ],200);
        }

    }

    /**
     * Display a listing of the resource.
     */
   /**
     * Ver juda previa a un intento X
     * @OA\Post (
     *     path="/game/previo",
     *     tags={"Game"},
     *      @OA\Parameter(
     *          name="intento",
     *          in="query",
     *          description = "Intento que desea mostrar",
     *          example = "5",
     *          required=true,
     *          @OA\schema(type="number")
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="la Combinacion evaluada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id_juego",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="inicio_juego",
     *                         type="string",
     *                         example="2023-11-28T15:46:17.467752Z"
     *                     ),
     *                     @OA\Property(
     *                         property="tiempo_juego",
     *                         type="number",
     *                         example="800"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         example="Juego creado satisfactoriamente.Su id de juego es :8), El contador de juego comienza en 2023-11-28 15:46:17 y tiene un tiempo de 800 antes de perder la partida."
     *                     ),
     *                    
     *                 )
     *             )
     *         )
     * ),
     *     @OA\Response(
     *         response=428,
     *         description="Error de Valiacion",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         example="Error de validacion"
     *                     ),
     *                     @OA\Property(
     *                         property="error",
     *                         type="string",
     *                         example="El campo X de ser..."
     *                     ),
     *                     
     *                 )
     *             )
     *         )
     *     )                                     
     * )
     *     @OA\Response(
     *         response=401,
     *         description="La combinacion no existe",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="status",
     *                         type="string",
     *                     ),
     *                     @OA\Property(
     *                         property="mensaje",
     *                         type="string",
     *                     ),
     *                     
     *                 )
     *             )
     *         )
     *     )                                     
     * )
     * 
     */
    public function previa(Request $request)
    {
        $rules = ['intento'=>'required|integer|max:40'];
        $validator =\Validator::make($request->input(),$rules);
        if($validator->fails())
        {
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()->all(),

            ],428);
        }
        $timer= new GameTimer();
        $juego = Juego::find($timer->get_juego());
        if($juego)
        {
            $previa = $this->busca_previa($juego->id,$request->intento);
            if($previa!= false)
            {
                return response()->json([
                    'status'=>true,
                    'previo'=>$previa,
    
                ],200);
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"No existe intento previo",
    
                ],404);
            }
        }else{
            return response()->json([
                'status'=>false,
                'error'=>"Error al encontrar el juego",

            ],404);
        }   
    }
    //funcion que devuelve la combinacion jugada en un intento que es pasado por parametros
    public function busca_previa($id,$intento)
    {
     $juego = Juego::find($id);
     if($juego)
     {
       $combinaciones = $juego->combinaciones;
       $combi = explode('-',$combinaciones);
       if($intento>1&&array_key_exists($intento,$combi))
       {
        return $combi[$intento-1];
       }else{
        return false;
       }
     }else{
        return false;
     }  
    }
    public function ranking($juego)
    {

    }
    public function evaluar_juego($juego,$timer)
    {
        $eval = ($timer->ver_tiempo()/2)+$juego->intento;
        return $eval;
    }

    public function game_over($timer)
    {
        return config('custom.udv')<$timer->ver_tiempo()?TRUE:FALSE;
    }
    public function generar_combinacion_inicial()
    {
        $combinacion_inicial = "";
        while(strlen($combinacion_inicial)<4)
        {
            $digito = mt_rand(0,9);
            if(strpos($combinacion_inicial,(string)$digito)===false)
            {
                 $combinacion_inicial.=$digito;
            }

        }
        return $combinacion_inicial;
    }
}
