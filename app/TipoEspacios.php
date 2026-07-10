<?php

namespace App;

enum TipoEspacios: string
{
    case Personal = 'personal';
    case Pareja =  'pareja';
    case Familia = 'familia';
    case Negocio = 'negocio';
    case Otro = 'otro';
}
