<?php

declare(strict_types=1);

namespace App\Renderer;

use Illuminate\Support\Facades\Log;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Xml\XmlNodeRendererInterface;

final class HeadingRenderer implements NodeRendererInterface, XmlNodeRendererInterface {
    public static function __set_state($an_array = null) {
        return true;
    }

    /**
     * @param Heading $node
     *
     * {@inheritDoc}
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable {
        Heading::assertInstanceOf($node);

        $text = new Text(str_repeat('#', $node->getLevel()) . ' ');

        $attrs = $node->data->get('attributes');

        return new HtmlElement('p', $attrs, $childRenderer->renderNodes([$text, ...$node->children()]));
    }

    public function getXmlTagName(Node $node): string {
        return 'heading';
    }

    /**
     * @param Heading $node
     *
     * @return array<string, scalar>
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function getXmlAttributes(Node $node): array {
        Heading::assertInstanceOf($node);

        return ['level' => $node->getLevel()];
    }
}