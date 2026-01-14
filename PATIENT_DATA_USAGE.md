# Como usar PatientData para POST e PUT

A classe `PatientData` agora funciona tanto para operações de **criação (POST)** quanto para **atualização (PUT)** de pacientes.

## Funcionamento

### Para POST (Criar novo paciente)
```php
// Controller store method
public function store(PatientData $request)
{
    $data = $this->patientService->create($request);
    return response()->json($data, Response::HTTP_CREATED);
}
```

**Request JSON para POST:**
```json
{
    "first_name": "João",
    "last_name": "Silva",
    "document": "12345678901",
    "active": true,
    "notes": "Paciente novo"
}
```

### Para PUT (Atualizar paciente existente)
```php
// Controller update method
public function update(string $uuid, PatientData $request): JsonResponse
{
    // O uuid é automaticamente adicionado ao payload para validação
    $request->uuid = $uuid;
    
    $data = $this->patientService->update($uuid, $request);
    return response()->json($data, Response::HTTP_OK);
}
```

**Request JSON para PUT:**
```json
{
    "first_name": "João",
    "last_name": "Santos",
    "document": "12345678901",
    "active": true,
    "notes": "Paciente atualizado"
}
```

## Validações Automáticas

### Para POST (criação):
- `document` deve ser único na tabela `patients`
- Todos os campos obrigatórios devem estar presentes

### Para PUT (atualização):
- `document` deve ser único, **exceto** para o registro atual (ignorado pelo UUID)
- Permite manter o mesmo documento do paciente sendo atualizado

## Regras de Validação

- **first_name**: obrigatório, string, min 2 caracteres, max 100
- **last_name**: obrigatório, string, min 2 caracteres, max 100  
- **document**: obrigatório, string, min 11 caracteres, único (com contexto)
- **active**: obrigatório, boolean
- **notes**: opcional, string, max 500 caracteres

## Vantagens da Implementação

1. **Código unificado**: Uma única classe Data para ambas operações
2. **Validação inteligente**: Detecta automaticamente se é criação ou atualização
3. **Reutilização**: Evita duplicação de código de validação
4. **Manutenibilidade**: Mudanças de validação em um local só
5. **Consistência**: Mesmas regras aplicadas em ambos os contextos
