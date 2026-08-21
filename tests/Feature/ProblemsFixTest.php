<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Movimiento;
use Livewire\Livewire;
use App\Livewire\Movimientos;
use App\Livewire\Clientes;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProblemsFixTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test 1: Movimientos search does not crash with PostgreSQL numeric casting and finds keyword.
     */
    public function test_movimientos_search_with_keyword_works_and_does_not_crash_sql()
    {
        $cliente = Cliente::create([
            'cod_fca' => 'FCA-101',
            'nombre' => 'Bodega La Palma',
            'domicilio' => 'Calle 1',
            'telefono' => '123456',
            'email' => 'bodega@test.com',
            'contacto' => 'Juan',
            'rut' => '20-12345678-9',
        ]);

        $movimiento = Movimiento::create([
            'cliente_id' => $cliente->id,
            'tipo_mov' => 'Ingreso',
            'detalle' => 'Cosecha 2026 Varietal',
            'cantidad' => 1500.00,
            'fecha' => '2026-08-20',
        ]);

        // Search by detail
        Livewire::test(Movimientos::class)
            ->set('keyWord', 'Cosecha')
            ->assertSee('Cosecha 2026 Varietal')
            ->assertSee('1.500,00');

        // Search by client name
        Livewire::test(Movimientos::class)
            ->set('keyWord', 'Bodega La Palma')
            ->assertSee('Cosecha 2026 Varietal');

        // Search by numeric ID (previously failed with operator does not exist: bigint ~~* unknown)
        Livewire::test(Movimientos::class)
            ->set('keyWord', (string)$cliente->id)
            ->assertSee('Cosecha 2026 Varietal');
    }

    /**
     * Test 2: Movimientos store normalizes negative "Ingreso" into positive value.
     */
    public function test_movimientos_store_normalizes_negative_ingreso()
    {
        $cliente = Cliente::create([
            'cod_fca' => 'FCA-102',
            'nombre' => 'Olivar del Valle',
        ]);

        Livewire::test(Movimientos::class)
            ->set('cliente_id', $cliente->id)
            ->set('tipo_mov', 'Ingreso')
            ->set('detalle', 'Ingreso con valor negativo ingresado por error')
            ->set('cantidad', '-250.50')
            ->set('fecha', '2026-08-21')
            ->call('store');

        $this->assertDatabaseHas('movimientos', [
            'cliente_id' => $cliente->id,
            'tipo_mov' => 'Ingreso',
            'cantidad' => 250.50,
        ]);
    }

    /**
     * Test 3: Movimientos store normalizes positive "Salida" into negative value.
     */
    public function test_movimientos_store_normalizes_positive_salida()
    {
        $cliente = Cliente::create([
            'cod_fca' => 'FCA-103',
            'nombre' => 'Aceites Cuyanos',
        ]);

        Livewire::test(Movimientos::class)
            ->set('cliente_id', $cliente->id)
            ->set('tipo_mov', 'Salida')
            ->set('detalle', 'Extraccion tambores')
            ->set('cantidad', '100.00')
            ->set('fecha', '2026-08-21')
            ->call('store');

        $this->assertDatabaseHas('movimientos', [
            'cliente_id' => $cliente->id,
            'tipo_mov' => 'Salida',
            'cantidad' => -100.00,
        ]);
    }

    /**
     * Test 4: Clientes search resets page and supports case-insensitive search.
     */
    public function test_clientes_search_resets_page_and_searches_case_insensitively()
    {
        Cliente::create(['cod_fca' => 'AAA-1', 'nombre' => 'Aceitera Central']);
        Cliente::create(['cod_fca' => 'BBB-2', 'nombre' => 'Finca Los Andes']);

        $component = Livewire::test(Clientes::class)
            ->call('setPage', 2)
            ->set('keyWord', 'central');

        $this->assertEquals(1, $component->get('paginators')['page'] ?? 1);
        $component->assertSee('Aceitera Central');
        $component->assertDontSee('Finca Los Andes');
    }

    /**
     * Test 5: Mermas form validation fails gracefully with errors when no client is selected and todos is off.
     */
    public function test_mermas_aplicaratodos_validation_when_no_clients_selected()
    {
        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')
            ->from('/mermas')
            ->post('/aplicaratodos', [
                'porcentaje' => '5',
                'detalle' => 'Merma anual',
                // multi_cliente omitted and todos omitted
            ]);

        $response->assertSessionHasErrors(['multi_cliente']);
        $response->assertRedirect('/mermas');
    }

    /**
     * Test 6: Mermas handles decimal percentages with floatval without truncating.
     */
    public function test_mermas_calculates_decimal_percentages_correctly()
    {
        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        $user = User::factory()->create();

        $cliente = Cliente::create([
            'cod_fca' => 'FCA-200',
            'nombre' => 'Productor San Martin',
        ]);

        // Stock inicial de 1000 litros
        Movimiento::create([
            'cliente_id' => $cliente->id,
            'tipo_mov' => 'Ingreso',
            'detalle' => 'Ingreso inicial',
            'cantidad' => 1000.00,
            'fecha' => '2026-08-01',
        ]);

        // Aplicar 2.5% de merma (1000 * 0.025 = 25 litros)
        $response = $this->actingAs($user, 'web')->post('/aplicaratodos', [
            'porcentaje' => '2.5',
            'detalle' => 'Merma por decantacion 2.5%',
            'multi_cliente' => [$cliente->id],
        ]);

        $response->assertRedirect('/movimientos');
        $response->assertSessionHas('message', 'Mermas aplicadas con éxito.');

        $this->assertDatabaseHas('movimientos', [
            'cliente_id' => $cliente->id,
            'tipo_mov' => 'Salida',
            'detalle' => 'Merma por decantacion 2.5%',
            'cantidad' => -25.00,
        ]);
    }

    /**
     * Test 7: Mermas can apply to all clients when todos is checked.
     */
    public function test_mermas_applies_to_all_clients_when_todos_is_checked()
    {
        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        $user = User::factory()->create();

        $c1 = Cliente::create(['cod_fca' => 'C-1', 'nombre' => 'Cliente 1']);
        $c2 = Cliente::create(['cod_fca' => 'C-2', 'nombre' => 'Cliente 2']);

        Movimiento::create(['cliente_id' => $c1->id, 'tipo_mov' => 'Ingreso', 'detalle' => 'Stock', 'cantidad' => 500.00, 'fecha' => '2026-08-01']);
        Movimiento::create(['cliente_id' => $c2->id, 'tipo_mov' => 'Ingreso', 'detalle' => 'Stock', 'cantidad' => 200.00, 'fecha' => '2026-08-01']);

        $response = $this->actingAs($user, 'web')->post('/aplicaratodos', [
            'porcentaje' => '10',
            'detalle' => 'Merma general 10%',
            'todos' => 'on',
        ]);

        $response->assertRedirect('/movimientos');

        $this->assertDatabaseHas('movimientos', [
            'cliente_id' => $c1->id,
            'tipo_mov' => 'Salida',
            'cantidad' => -50.00,
        ]);

        $this->assertDatabaseHas('movimientos', [
            'cliente_id' => $c2->id,
            'tipo_mov' => 'Salida',
            'cantidad' => -20.00,
        ]);
    }
}
