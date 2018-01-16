<?php

/**
 *  @package        SOFIAH.App.Http.Controllers.Api
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 02-11-2017.
 *  @version        1.0
 * 
 *  @final  
 */

/**
 * Incluye la implementación de los siguientes Controladores y Modelos
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;

use App\Entities\Administrative\Partner;


/**
 *  Controlador Asociación
 */

class FilesController extends Controller
{
    /**
     * Exporta todo el listado de los asociados registrados en la BD
     *
     * @return File
     */
    public function exportPartners(Request $request)
    {

        \Excel::create('Partners', function($excel) {

            # Consultamos todos los asociados

            $partners = Partner::all();
            

            # Configuramos los parametros del archivo excel 

            $excel->setTitle('Listado Asociados - SOFIAH');

            $excel->setCreator('Sofiah')->setCompany('Idepixel');

            $excel->setDescription('Listado de todos los asociados registrados.');

            # Personalizamos el documento

            $excel->sheet('Asociados', function($sheet) use($partners)
            {

                # Formateamos el encabezado del archivo

                $sheet->row(1, [
                                'Código Empleado', 
                                'Cédula', 
                                'Nombre', 
                                'Apellido', 
                                'Teléfono', 
                                'Correo', 
                                'Estatus', 
                                'Fecha de Ingreso', 
                                'Organismo' ]);

                # Seleccionamos los campos a imprimir 

                foreach($partners as $index => $partner) 
                {
                    $sheet->row($index+2, [
                                            $partner->employee_code, 
                                            $partner->id_card, 
                                            $partner->names, 
                                            $partner->lastnames, 
                                            $partner->local_phone,
                                            $partner->email,
                                            $partner->status,
                                            ' ',
                                            $partner->organism->name ]); 
                } 


            });

        })->export($request->type_file);

    

        return response()->json([

            'status'    => true,
            'message'   => '¡El archivo se ha generado con éxito!'
        ]);

        

    }

    /**
     * Importa todo el listado de asociados recibidos en un archivo
     *
     * @return Json
     */
    public function importPartners(Request $request)
    {

        \Excel::load($request->excel, function($reader) {
 
            $excel = $reader->get();
 
            // iteracción
            $reader->each(function($row) {
 
                // $partner = new Partner;

                $partner->codigo = $row->Código;
                $partner->email = $row->Asociado;

                echo $partner->codigo, "<br>";
                echo $partner->email,  "<br>";

                //$partner->save();
 
            });
    
        });
 
        return response()->json([ 
            'status'  => true, 
            'message' => '¡El listado de asociados se ha registrado exitosamente!',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
