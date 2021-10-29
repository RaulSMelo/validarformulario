<?php

namespace RSM;

/**
 * Classe responsável por validar os inputs do formulário
 * 
 * @example <input name='name-required@email'> Para que a classe funcione corretamente é nessessário 
 * declarar o valor do atributo name do input como no exemplo.
 * name - nome literal do atributo name input 
 * required - (OBS: Opcional) mais sempre nessessário quando o o valor de entrada do input é obrigatório (required)
 * email - tipo do filtro para aplicar no valor de entrada do input
 * 
 * @author Raul Soares de Melo <raul.melo0901.ti@gmail.com>
 */
class ValidateForm{

    /**
     * Guarda os valores de entrada dos input POST
     *
     * @var array
     */
    private $post;


    /**
     * Contrutor da classe 
     *
     * @param array $post Array super global $_POST
     */
    public function __construct( $post )
    {
        $this->post = $post;
    }

    /**
     * Método responsável por retornar um novo array com os valores válidos [ $key => $value ].
     * Caso um dos valores de entrada do input POST não corresponder ao filtros de validação e retornado false.
     * 
     * @return mixed array | boolean
     */
    public function getPostsValid()
    {
        $dados = [];

        if( count( $this->post ) > 0 ){

            $keys = array_keys( $this->post );

            foreach ( $keys as $key ) {

                if( !$this->keyName( $key ) || is_null( $this->inputData( $key ) ) ){

                    $dados[$this->keyName( $key )] = null;
                    
                }else if( !$this->keyName( $key ) || !$this->inputData( $key )  ){

                    return false;
                    
                }else{

                    $dados[$this->keyName( $key )] = $this->inputData( $key );
                }
            }

        }else{

            return false;
        }

        return $dados;
    }

    /**
     * Método responsável por aplicar o filtro específico do input
     *
     * @param string $postName valor do atributo name do input
     * @return mixed 
     */
    private function inputData( $postName )
    {
        $params = $this->typeFilter( $postName );

        if( count( $params ) == 0) return false;

        $var = filter_input( INPUT_POST, $postName, FILTER_SANITIZE_STRING );

        switch ( $params[1] ) {
            
            case 'string':

                return $this->validateInput( $params[0], $var );

            case 'int':

                return $this->validateInput( $params[0], $var, FILTER_VALIDATE_INT );            

            case 'email':

                return $this->validateInput( $params[0], $var, FILTER_VALIDATE_EMAIL );
                
            case 'float':

                $var = str_replace( '.', '', $var );

                $var = str_replace( ',', '.', $var );

                return $this->validateInput( $params[0], $var, FILTER_VALIDATE_FLOAT);
        }
    }

    /**
     * Método responsável por separar o valor do atributo name, utilizando o @ como separador 
     * onde a 2° posição do array e o tipo do filtro (int | string | float | email), para aplicar no respectivo no input.
     *
     * @param string $postName valor do atributo name do input
     * @return array
     */
    private function typeFilter( $postName )
    {
        $ex = mb_strpos( $postName, '@' ) ? explode( '@', $postName ) : [];

        return $ex;
    }

    /**
     * Método responsável por verificar se existe a ocorrência (required) no valor do atributo name do input.
     * Caso exista é retornado true, se não existir retorna false.
     *
     * @param string $str
     * @return boolean
     */
    private function isRequiredField( $str )
    {
        $ex = mb_strpos( $str, '-' ) ? explode( '-', $str ) : [];

        return ( count( $ex ) > 0 && $ex[1] == 'required' ) ? true : false;
    }

    /**
     * Método responsável por verificar se existe a ocorrência '@' ou '-'. Caso não existir é retornado false.
     * Se existir e transfomado em array e retornado sempre a 1° posição.
     * 
     * @param string $str
     * @return mixed
     */
    private function keyName( $str )
    {
        if( mb_strpos( $str, '-') ){

            $ex = explode( '-', $str );

            return $ex[0];
        
        }else if( mb_strpos( $str, '@') ){

            $ex = explode( '@', $str );

            return $ex[0];
        }

        return false;
    }

    /**
     * Método responsável por verificar e aplicar o filtro nas entradas dos input POST.
     * 
     * 1 - Verifica se o valor de entrada do input e obrigatório (required)
     * 2 - Verifica se o valor de entrada corresponde ao filtro
     * 3 - Verifica se existe valor de entrada, mesmo não sendo obrigatório (required) no input e aplica o filtro  
     *
     * @param string $str_required valor do atributo name do input
     * @param string $var valor de entrada do input
     * @param int $filter tipo do filtro 
     * @param array $options flags do filtro
     * @return mixed null | boolean | string => valor do input filtrado
     */
    private function validateInput( $str_required, $var, $filter = false, $options = [] )
    {
        if( $filter ){

            if( $this->isRequiredField( $str_required ) ){

                if( empty( $var ) ){
        
                    return false;
        
                }else{
        
                    if( filter_var( $var, $filter )  ){
        
                        return $var;
        
                    }else{
        
                        return false;
                    }
                }
        
            }else{
        
                if( empty( $var ) ){
        
                    return null;
        
                }else{
        
                    if( filter_var( $var, $filter ) ){
        
                        return $var;
        
                    }else{
        
                        return false;
                    }
                }
            }
        }else{

            if( $this->isRequiredField( $str_required ) ){

                if( empty( $var ) ){

                    return false;

                }else{

                    return $var;
                }

            }else{

                if( empty( $var ) ){

                    return null;

                }else{

                    return $var;
                }

            }
        }
    }
}