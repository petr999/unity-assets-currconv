<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Currency conversion controller.
 * @Route( "/api/v0", name="api_v0", defaults = { "_format" : "json" } )
 */
class CurrConvController extends AbstractController
{
    /**
     * @Route("/curr/conv", name="curr_conv" )
     */
    public function curr_conv( Request $request, ValidatorInterface $validator ): Response
    {
        $errorsCount = 0;
        $errors = [];

        $query = $request->query;
        $stringConstraint = new Assert\Type( [ 'type' => 'string', ] );
        $numericConstraint = new Assert\Type( [ 'type' => 'numeric', ] );
        $notBlankConstraint = new Assert\NotBlank(  );

        list( $baseCurr, $baseSum, $targCurr ) = [
          $query->get( 'baseCurr' ),
          $query->get( 'baseSum'  ),
          $query->get( 'targCurr' ),
        ];

        // targCurr
        $errorsSum = $validator->validate( $baseSum, $notBlankConstraint );
        $errorsSumCount = count( $errorsSum );
        if( 0 == $errorsSumCount ){
          $errorsSum = $validator->validate( $baseSum, $numericConstraint );
        }
        $errorsSumCount = count( $errorsSum );

        if( 0 < $errorsSumCount ){
          $errorsCount += $errorsSumCount;
          $errors[ 'baseSum' ] = (string) $errorsSum;
        }

        // baseCurr
        $errorsBaseCurr = $validator->validate( $baseCurr, $notBlankConstraint );
        $errorsBaseCurrCount = count( $errorsBaseCurr );
        if( 0 == $errorsBaseCurrCount ){
          $errorsBaseCurr = $validator->validate( $baseCurr, $stringConstraint );
        }
        $errorsBaseCurrCount = count( $errorsBaseCurr );

        if( 0 < $errorsBaseCurrCount ){
          $errorsCount += $errorsBaseCurrCount;
          $errors[ 'baseCurr' ] = (string) $errorsBaseCurr;
        }

        // targCurr
        $errorsTargCurr = $validator->validate( $targCurr, $notBlankConstraint );
        $errorsTargCurrCount = count( $errorsTargCurr );
        if( 0 == $errorsTargCurrCount ){
          $errorsTargCurr = $validator->validate( $targCurr, $stringConstraint );
        }
        $errorsTargCurrCount = count( $errorsTargCurr );

        if( 0 < $errorsTargCurrCount ){
          $errorsCount += $errorsTargCurrCount;
          $errors[ 'targCurr' ] = (string) $errorsTargCurr;
        }

        if( 0 < $errorsCount ){
          throw new BadRequestHttpException( json_encode( $errors, JSON_UNESCAPED_UNICODE ) );
        }

        return $this->json([
        ]);
    }

}
