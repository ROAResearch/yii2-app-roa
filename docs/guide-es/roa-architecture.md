Arquitectura ROA
================

La arquitectura ROA utiliza modulos de contenedor de api,  modulos de versión
de api y clases para definir recursos e interfaces de servicios.

Contenedor de API
-----------------

El contenedor de versiones de api se configura como modulo de la aplicación.

Dentro del contenedor se pueden definir distintas versiones que son
independientes entre ellas de forma que cada versión define todos los recursos
necesarios para la utilización completa de los flujos de negocio del api.


Declaración Versiones de API
----------------------------

Cada api puede soportar varias versiones con distintos niveles de estabilidad.

Cada versión se declara en el arreglo `ApiContainer::$versions` donde la llave
del arreglo es el id único de cada versión y el valor corresponde a la
configuración.

Por defecto esto se declara en los archivos `backend/api/config.php` y
`frontend/api/config.php`

```php
'versions' => [
    'v1' => [
        'class' => ApiVersion::class,
        // optional default value {uniqueId}\\resources
        'controllerNamespace' => 'backend\\api\\v1\\resources',
        'stability' => ApiVersion::STABILITY_DEPRECATED,
        'resources' => [
            // list of resources for v1
            'store',
            'aisles',
        ],
    ],
    'v2' => [
        'class' => ApiVersion::class,
        'stability' => ApiVersion::STABILITY_STABLE,
        'resources' => [
            // list of resources for v2
            'store',
            'store/<store_id:\d+>/aisle' => v2\AislesController::class,
        ],
    ]
]
```

Se recomienda crear carpetas con estructura modelo-controlador para cada versión
y dentro de estas carpetas definir archivos de configuración independientes para
cada versión.

```php
'versions' => [
    'v1' => ArrayHelper::merge(
        require(__DIR__ . 'v1/config.php'),
        require(__DIR__ . 'v1/config-local.php'),
    ),
    'v2' => ArrayHelper::merge(
        require(__DIR__ . 'v2/config.php'),
        require(__DIR__ . 'v2/config-local.php'),
    ),
]
```

Se recomienda definir todos estos valores en archivos de configuración

### Estabilidad

La estabilidad de cada versión define como se  va a llevar su desarrollo y
mantenimiento.

#### Desarrollo

Los recursos e interfaces no se consideran publicados por lo que pueden ser
alterados y añadir nuevos recursos 

##### Politicas

- NO DEBE haber más de un mismo tipo de api en desarrollo.

#### Estable

Al publicarse una versión se convierte es estable, esto significa que ya no se
desarrollan nuevas funcionalidades y se da mantenimiento activo conforme la
retroalimentación del usuario final.

Todo mantenimiento debe ser retroincompatible con los recursos e interfaces
publicadas a partir de la fecha de liberación.

##### Politicas

- NO DEBERÍA haber más de un mismo tipo de api estable.
- NO DEBERÍAN de publicarse nuevos recursos.
- NO DEBEN eliminarse recursos publicados.
- NO DEBE de eliminarse funcionalidad de una recurso publicado.
- Las interfaces NO DEBEN eliminar atributos de la estructura de información.
- Se corrigen activamente fallos de ejecución y seguridad.


#### Deprecado

Ya no se soporta activamente, correcciones de ejecución se ignoran y sólo se da
soporte a fallos de seguirdad para el cliente final y el servidor.

##### Politicas

- NO DEBERÍA haber más de 2 apis deprecadas del mismo tipo
- NO DEBEN publicarse nuevos recursos o funcionalidades
- NO DEBERÍAN eliminarse recursos publicados.
- Los recursos eliminando DEBEN devolver un código de estado HTTP 410 GONE
- NO DEBERÍA eliminarse la funcionalidad de un recurso publicado
- Las interfaces NO DEBEN eliminar atributos de la estructura de información.
- Sólo se corrigen fallos de seguridad para el usuario final o el servidor.
- NO DEBEN corregirse errores de ejecución
. DEBERÍA recibir mantenimiento de seguridad por al menos 6 meses.
- NO DEBERÍA recibir mantenimiento de seguridad por más de 12 meses.

No debería haber más dos versiones deprecadas a la vez.

Se recomienda mantener una versión en estatilidad deprecada por al menos 6
meses para permitir la migración de aplicaciones que consuman cada versión.

#### No Soportado

Al llegar al final del ciclo de vida, la api y los recursos ya no están
disponibles para su consumo.

##### Politicas

- Todos los recursos DEBEN devolver un código de estado HTTP 410 GONE

### Publicación

Se le llama 'publicación' al proceso de cambiar la estabilidad de una versión
de desarrollo a estable.

Una vez que se han completado los recursos para consumir los flujos de negocio
de la aplicación y estos han sido verificados como funcionales, se puede cambiar
la estabilidad de un modulo de versión.

Para publicar un modulo de versión basta con definir el atributo `$releaseDate`
con la fecha a partir de la cual el modulo se considera estable.

```php
    'releaseDate' => '2020-06-15',
```

### Deprecación

Se le llama 'deprecación' al proceso de cambiar la estabilidad de una versión de
estable a deprecada.

Generalmente se asocia el publicar una versión nueva con deprecar la versión
anteriormente soportada.

Para deprecar un modulo de versión es necesario definir los atributos
`$deprecationDate` y `$endOfLifeDate`.

```php
    'releaseDate' => '2020-06-15',
    'deprecationDate' => '2021-06-01',
    'endOfLifeDate' => '2021-12-31',
```

### Fin de Ciclo de Vida

Se le llama 'fin de ciclo de viad' al proceso de cambiar la estabilidad de una
versión de deprecada a no soportada.

Este proceso es automatico en cuanto el contenedor detecta una versión con una
fecha `$endOfLifeDate` menor a la fecha actual.
