<table border="1" cellspacing="0" cellpadding="2">
<thead>
    <tr style="font-weight: bold;">
        <th align="center" colspan="4">Empresa</th>
        <th align="center" colspan="1">Casos Positivos</th>
        <th align="center" colspan="1">Sospechosos</th>
    </tr>
</thead>
<tbody>
    <?php foreach($datos as $empresa => $value):?>
        <?php if (!($value[0] == 0 && $value[1] == 0)):?>
            <tr>
                <td colspan="4"><?= $empresa ?></td>
                <td align="center" colspan="1"> <?= number_format($value[0], 0, ',', '.') ?></td>
                <td align="center" colspan="1"> <?= number_format($value[1], 0, ',', '.') ?></td>
            </tr>      
        <?php endif;?>
    <?php endforeach;?>
</tbody>
</table>