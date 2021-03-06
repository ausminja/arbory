<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

class Link extends HasOne
{
    /**
     * @param string $name
     */
    public function __construct( string $name )
    {
        $fieldSetCallback = function( FieldSet $fieldSet )
        {
            $fieldSet->add( new Text( 'href' ) );
            $fieldSet->add( new Text( 'title' ) );
            $fieldSet->add( new Checkbox( 'new_tab' ) );
        };

        parent::__construct( $name, $fieldSetCallback );
    }

    /**
     * @return Element
     */
    public function render()
    {
        return Html::section( [
            $this->getHeader(),
            $this->getBody(),
        ] )->addClass( 'nested' );
    }

    /**
     * @return Element
     */
    protected function getHeader()
    {
        return Html::div( Html::label( $this->getLabel() ) )->addClass( 'label-wrap' );
    }

    /**
     * @return Element
     */
    protected function getBody()
    {
        $item = $this->getValue() ?: $this->getRelatedModel();

        $block = Html::div()->addClass( 'link-body' );

        $fieldSetHtml = Html::fieldset()->addClass( 'item' );

        foreach( $this->getRelationFieldSet( $item )->getFields() as $field )
        {
            $fieldSetHtml->append( $field->render() );
        }

        return $block->append( $fieldSetHtml );
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        $rules = $this->rules[ 0 ] ?? null;

        if( $rules )
        {
            $this->fieldSetCallback = function( FieldSet $fieldSet ) use ( $rules )
            {
                $fieldSet->add( new Text( 'href' ) )->rules( $rules );
                $fieldSet->add( new Text( 'title' ) )->rules( $rules );
                $fieldSet->add( new Checkbox( 'new_tab' ) );
            };
        }

        unset( $this->rules );

        return parent::getRules();
    }
}
