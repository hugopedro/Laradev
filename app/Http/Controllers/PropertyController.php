<?php

namespace LaraDev\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LaraDev\Models\Property;

class PropertyController extends Controller
{
    public function index() {

        //$properties = DB::select("SELECT * FROM properties") equivale a linha abaixo;
        $properties = Property::all();

        return view('property/index')->with('properties', $properties);
    }

    public function show($name) {
        //pesquisa no BD usando o id, e caso tenha alimenta a visão
        //$property = DB::select("SELECT * FROM properties WHERE name = ?", [$name]);
        //EQUIVALE A LINHA DE BAIXO!
        $property = Property::where('name', $name)->get();

        if (!empty($property)) {
            // chama a view
            return view('property/show')->with('property', $property);
        } else {
            return redirect()->action([PropertyController::class, 'index']);
        }
    }

    public function create() {
        return view('property/create');
    }

    public function store(Request $request) {

        $propertySlug = $this->setName($request->title);

        /* Recaptulando: Supondo que existe dois imóveis cadastrados na tabela, o foreach vai percorrer
        duas vezes este loop, e cada uma das posições adiciona-se o str_slug na propiedade title, isso
        verifica se o retorno é exatamente igual ao imóvel que já alimentou acima (Linha 36)... por
        exemplo se eu cadastro um imóvel titulo e vou cadastrar outro imóvel tambem com nome título
        os nomes ficam duplicados, então esse processo vai passar duas vzes dentro do bloco foreach,
        ao passar alimenta-se a variável $t++ para concatenar o nome do título com um número contador,
        daí em vez de ficar Nome Nome fica Nome Nome 1, dái evita dupes. */
        /*
        //$property = [
            $request->title,
            $propertySlug,
            $request->description,
            $request->rental_price,
            $request->sale_price
        //];

        DB::insert("INSERT INTO properties (title, name, description, rental_price, sale_price) VALUES
                   (?, ?, ?, ?, ?)", $property);

        EQUIVALE AO CÓDIGO ABAIXO: */

            $property = [
            'title' => $request->title,
            'name' => $propertySlug,
            'description' => $request->description,
            'rental_price' => $request->rental_price,
            'sale_price' => $request->sale_price
        ];

        Property::create($property);

        return redirect()->action([PropertyController::class, 'index']);
    }

    public function edit($name)
    {
        //pesquisa no BD usando o id, e caso tenha alimenta a visão
        //$property = DB::select("SELECT * FROM properties WHERE name = ?", [$name]);
        //EQUIVALE A LINHA DE BAIXO!
        $property = Property::where('name', $name)->get();

        if (!empty($property)) {
            // chama a view
            return view('property/edit')->with('property', $property);
        } else {
            return redirect()->action([PropertyController::class, 'index']);
        }
    }

    public function update(Request $request, $id)
    {
        $propertySlug = $this->setName($request->title);

        /* //$property = [
            $request->title,
            $propertySlug,
            $request->description,
            $request->rental_price,
            $request->sale_price,
            $id
        //];

        DB::update("UPDATE properties SET title = ?, name = ?, description = ?,
                      rental_price = ?, sale_price = ?
        WHERE id = ?", $property);

        EQUIVALE AO CODIGO ABAIXO UTILIZANDO O FRAMEWORK: */

        $property = Property::find($id);
        $property->title = $request->title;
        $property->name = $propertySlug;
        $property->description = $request->description;
        $property->rental_price = $request->rental_price;
        $property->sale_price = $request->sale_price;

        $property->save();

        return redirect()->action([PropertyController::class, 'index']);
    }

    public function destroy($name) {
        //$property = DB::select("SELECT * FROM properties WHERE name = ?", [$name]);
        //EQUIVALE A LINHA ABAIXO
        $property = Property::where('name', $name)->get();

        if(!empty($property)) { //verifica se teve algum retorno
            DB::delete("DELETE FROM properties WHERE name = ?", [$name]);
        }

        //redireciona novamente para a listagem

        return redirect()->action([PropertyController::class, 'index']);
    }

    // é preciso criar um método pra caso mude o nome do imovel, fazer a verificação novamente

    private function setName($title) {
        //converte o título para uma url que seja válida, ou seja, vai converter todos os chars para
        // minusculo, cedilhas etc. é usada a função slug para isso.
        $propertySlug = str_slug($title);
        //verifica se já tem outros registros com o mesmo valor, pois não vai rodar se tiver duplicado
        //$properties = DB::select("SELECT * FROM properties");
        //EQUIVALE A LINHA ABAIXO
        $properties = Property::all();
        $t = 0;
        foreach($properties as $property) {
            // se o titulo for totalmente igual...
            if(str_slug($property->title) === $propertySlug) {
                $t++;
            }
        }

        if ($t > 0 ) {
            $propertySlug = $propertySlug. '-' . $t;
        }

        return $propertySlug;
    }

}
