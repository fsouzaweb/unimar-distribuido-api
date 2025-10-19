<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenApi\Generator;

class GenerateSwaggerDocs extends Command
{
    protected $signature = 'swagger:generate';
    protected $description = 'Gera a documentação Swagger da API';

    public function handle()
    {
        $this->info('Gerando documentação Swagger...');
        
        try {
            $openapi = Generator::scan([app_path()]);
            
            // Salva o arquivo JSON
            $swaggerPath = public_path('swagger.json');
            file_put_contents($swaggerPath, $openapi->toJson());
            
            $this->info("Documentação Swagger gerada com sucesso em: {$swaggerPath}");
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Erro ao gerar documentação: ' . $e->getMessage());
            return 1;
        }
    }
}