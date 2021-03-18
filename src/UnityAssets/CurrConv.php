<?php
namespace UnityAssets;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CurrConv{

  protected $exchQuotes = [];

  function __construct( $cbrUrl = 'http://www.cbr.ru/scripts/XML_daily.asp' ){
    $xmlString = file_get_contents( $cbrUrl );
    $exchQuotesXml = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);

    $exchQuotesArr = json_decode( json_encode( $exchQuotesXml ) , true )[ 'Valute' ];
    foreach( $exchQuotesArr as $exchQuote ){
      $charCode = $exchQuote[ 'CharCode' ];
      $value    = $exchQuote[ 'Value' ];
      $value = preg_replace( '/,/', '.', $value );

      $exchQuotes[ $charCode ] = $value;
    }

    $this->exchQuotes = $exchQuotes;
  }

  // Find if currency is listed
  function currExists( string $curr ): bool {
    $rv = ( 'RUB' != $curr ) && empty( $this->exchQuotes[ $curr ] );
    $rv = ! $rv;

    return $rv;
  }

  // Convert currency
  function convertBaseTargSum( $base, $targ, $baseSum ) {
    $sum = null;
    $exchQuotes = $this->exchQuotes;

    if( 'RUB' == $base ){
      if( 'RUB' == $targ ){
        $sum = $baseSum;
      } else {
        $quote = $exchQuotes[ $targ ];
        if( 0 == $quote ){
          throw new ConflictHttpException( "'${quote}': zero value" );
        }
        $sum = $baseSum / $quote;
      }
    } elseif( 'RUB' == $targ ){
      $quote = $exchQuotes[ $base ];
      $mult = 1 *  $quote;
    } else {
      $quoteBase = $exchQuotes[ $base ];
      $quoteTarg = $exchQuotes[ $targ ];
        if( 0 == $quoteTarg ){
          throw new ConflictHttpException( "'${quoteTarg}': zero value" );
        }
      $sum = $baseSum * $quoteBase / $quoteTarg;
    }

    return $sum;
  }

}


