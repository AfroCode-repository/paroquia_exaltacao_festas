<?php //Esse arquivo gerencia e une todas as Traits do sistema

namespace App\Traits;

trait mainTrait {

    use ApiResponser;
    use PagesTrait;
    use SystemTrait;
    use ControlGroup;
    use AgendamentoTrait;
    use ClientTrait;

}//fim classe

