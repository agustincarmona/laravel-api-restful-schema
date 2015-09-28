Publica el schema de entrada y salida de tu api
==========================
Los que habéis usado servicios web de ASP.NET o Java estaréis familiarizados con el formato WSDL. El objetivo de este formato es devolver la estructura de datos de entrada y datos de salida para cada método del API. Esto es un útil porque te permite montar objetos o clases dinámicamente  a partir del schema de un servicio web.

Pues bien, imaginar que estamos usando un API y queremos montar unos objetos para encapsular la respuesta del servicio web y trabajar esos datos. Nos tocaría hacer una transformación de esos datos  en nuestra estructura de clases.

Ahora imaginar que podéis construir los modelos dinámicamente sólo consultando el schema del servicio web, esto nos ahorraría tener que buscar la documentación para ver la nueva estructura, implementar las nuevas propiedades en nuestros modelos y adaptar nuestra aplicación.

**Instalación**


**Cómo se usa**

Añadir el trait APISchema\Http\Traits\SchemeTraitController en tú controlador.
{{{

class MyController extends Controller {
  use APISchema\Http\Traits\SchemeTraitController;

}

}}}

