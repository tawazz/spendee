<?php
namespace HTTP\Middleware;
use \HTTP\Middleware\BaseMiddleware as Middleware;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\{HtmlDumper,CliDumper};

class Dump extends Middleware{

    public function __invoke ($req,$resp,$next){
        $this->run();
        $resp = $next($req,$resp);
        return $resp;
    }

    public function run(){

        VarDumper::setHandler(function ($var){
            $cloner = new VarCloner();
            $htmlDumper = new htmlDumper;
            $cliDumper = new CliDumper;

            $htmlDumper->setStyles([
                'default' => 'background-color:#fff; color:#FF8400; line-height:1.2em; font:12px Menlo, Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:99999; word-break: normal',
                'public' => 'color:#333',
                'protected' => 'color:#333',
                'private' => 'color:#333',
            ]);
            $dumper = PHP_SAPI == 'cli' ? $cliDumper : $htmlDumper;
            $dumper->dump($cloner->cloneVar($var));
        });
    }

  }

  ?>
