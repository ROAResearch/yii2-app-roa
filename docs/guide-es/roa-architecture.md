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

Se le llama 'fin de ciclo de vida' al proceso de cambiar la estabilidad de una
versión de deprecada a no soportada.

Este proceso es automatico en cuanto el contenedor detecta una versión con una
fecha `$endOfLifeDate` menor a la fecha actual.

## Terminos y Equivalencias

Para la utilización de ROA se usan terminos que extienden funcionalidades de
Yii2.

ROA      | Yii2        | Diferencias
-------- | ----------- | -----------
Recurso  | Controlador | Se eliminan sesiones en favor de tokens, se definen verbos en lugar de acciones.
Verbo    | Acción      | Sólo se acceden mediante el metodo empleado por HTTP y se definen por recurso.
Interfaz | Modelo      | Se definen la estructura de información

### Interfaz

La interfaz también conocida como contrato define la estructura de la
información que recibe y devuelven los verbos de cada recurso.

#### Estructura de Cuerpo de la Respuesta.

Se utilizan modelos de yii2 utilizando [AttributeTypeCastBehavior] o algún otro
metodo para exponer el tipo de variabl de cada atributo que recibe y devuelve
cada modelo.

#### Links

Representan enlaces a recursos relacionados para facilitar la navegación del cliente.

```php
use yii\db\ActiveRecord;
use yii\web\Link;
use yii\web\Linkable;

class Order extends ActiveRecord implements Linkable
{//}
    public function getLinks()
    {//}
        return [
            Link::REL_SELF => Url::to(""),
            // html version
            'html' => [
                new Link([
                    'rel' => 'alternate',
                    'href' => Url::toRoute(['//order', 'id' => $this->id]),
                ])
            ],
            'docs' => [
                new Link([
                    'rel' => 'help',
                    'href' => Url::toRoute('swager.io/project/order', true),
                ])
            ],
            'customer' => Url::to("customer/{$this->customer_id}"),
            'store' => Url::to("store/{$this->store_id}"),
            'items' => Url::to("order/{$this->id}/items"),
        ];
    }
}
```  

### Control de Mensajes

#### Mensajes de Error y Exito.

En ROA se utiliza el control de mensajes mediante los códigos de [estado HTTP]
de forma que si la solicitud devuelve un estado 2xx significa que la solicitud
fue exitosa, 4xx error de usuario y 5xx error de servidor.

#### Cuerpo de la respuesta

El cuerpo de la respuesta sigue el esquema de [JSON Hypermedia].


```JSON
[
  {
    "id": 1,
    "nombre": "Angel Guevara",
    "_links": [
      "self": "/api/v1/user/1"
    ]
  }
]
```

#### Auto documentación

Mediante el metodo options se puede obtener una definifición de cada recurso al
usar el parametro `defs=1` en la URI.

Esta definición también funciona como documentación al seguir la especificacion
[OpenApi] de [Swagger]

#### Formatos

Se admiten formatos JSON y XML para el cuerpo de la respuesta de una petición.
Para controlar el formato de la respuesta se debe agregar el parametro `_format`
a la URI o usar el header `accept` en el request. Si no se usa ninguna de las
dos opciones se usará el formato `JSON` por defecto. Hay que notar que toda
petición desde un navegador agregara una cabecera `accept:application/xml` por
lo que de un navegador por defecto se mandara la respuesta en xml.

### Recurso

Los recursos son componentes de software discretos que pueden ser reusados para
distintos propositos. Cada recurso debe ser independiente del estado y del
consumo de otros recursos. Estan pensados para que cada ruta apunte a únicamente
un recurso y distintas rutas deben apuntar a distintos recursos.

De esta forma las rutas `orden` y `orden/productos` son distintos recursos y no
distintas acciones de un mismo recurso.

#### Clase PHP

Los recursos se definen creando controladores que extienden la clase
`tecnocen\roa\controllers\OAuth2Resource` la cual a su vez extiende
`yii\rest\ActiveController`.

Esta clase ya tiene soporte para las funcionalidades básicas de un recurso.

#### Patrones de rutas

Las rutas siguen los patrones y comportamientos de [UrlManager::$rules].

Cada recurso puede tener su propia ruta hacia un controlador especifico.

```php
'resources' => [
    'orden',
    // accepts /orden/1/producto
    'orden/<orden_id:[\d]+>/producto' => 'orden-producto',
    'entidad-federativa',
    // accepts /entidad-federativa/df/tienda
    'entidad-federativa/<entidad:[\w]{2}>/tienda' => 'tienda',
],
```

#### Paginación

Siguiendo el estandard de [JSON Hypermedia] la paginación no se puede añadir al
cuerpo de la respuesta si no que se usan las cabeceras `X-Pagination`.

Estas cabeceras son serializadas automaticamente por yii2 al devolver un objeto
que implemente `yii\data\DataProviderInterface`.

#### Cross Origin

Las peticiones de dominios cruzados se restringen y aprueban usando el metodo
`OAuth2Resource::cors()` que es consumido por `yii\filters\Cors` para definir
las cabeceras usadas por Cross-Origin Resource Sharing.

Esto permite definir dinamicamente las cabeceras para cada recurso y petición.

```php
protected function cors()
{
    return [
         // ...
    ];
}
```

#### Cache

La propiedad `Oauth2Resource::$enableCache` habilita cache de HTTP y la cache
de respuesta y crea archivos en el servidor que guardan la respuesta.

```php
protected $enableCache = true;
```

La cache se puede configurar usando metodos.

[estado HTTP]: https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
[AttributeTypeCastBehavior]: http://www.yiiframework.com/doc-2.0/yii-behaviors-attributetypecastbehavior.html
[JSON Hypermedia]: http://json-schema.org/latest/json-schema-hypermedia.html
[OpenApi]: http://www.swagger.io/specification/
[Swagger]: http://www.swagger.io/
[UrlManager::$rules]: http://www.yiiframework.com/doc-2.0/yii-web-urlmanager.html#$rules-detail
