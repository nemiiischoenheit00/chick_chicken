import java.awt.*;
import java.awt.event.*;
import javax.swing.*;
import javax.swing.table.DefaultTableModel;

public class activity3 extends Frame implements ActionListener {

    // Components
    JComboBox<String> cmbItems;
    JSpinner spnQty;
    JButton btnAdd, btnRemove, btnTotal;
    JTable table;
    DefaultTableModel model;
    JLabel lblTotal;

    // Arrays
    String[] items = {"Apple", "Banana", "Orange", "Mango", "Grapes", "Watermelon"};
    double[] prices = {25.00, 10.00, 20.00, 50.00, 100.00, 75.00};

    public activity3() {

        // Frame Settings
        setTitle("Point of Sales (POS) System");
        setSize(700, 500);
        setLayout(null);
        setVisible(true);

        // Close Button
        addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e) {
                dispose();
            }
        });

        // Labels
        Label lblItem = new Label("Select Item:");
        lblItem.setBounds(50, 60, 100, 30);
        add(lblItem);

        Label lblQty = new Label("Quantity:");
        lblQty.setBounds(50, 110, 100, 30);
        add(lblQty);

        // ComboBox
        cmbItems = new JComboBox<>(items);
        cmbItems.setBounds(160, 60, 150, 30);
        add(cmbItems);

        // Spinner
        spnQty = new JSpinner(new SpinnerNumberModel(1, 1, 100, 1));
        spnQty.setBounds(160, 110, 80, 30);
        add(spnQty);

        // Buttons
        btnAdd = new JButton("Add Item");
        btnAdd.setBounds(50, 170, 120, 35);
        btnAdd.addActionListener(this);
        add(btnAdd);

        btnRemove = new JButton("Remove Item");
        btnRemove.setBounds(190, 170, 140, 35);
        btnRemove.addActionListener(this);
        add(btnRemove);

        btnTotal = new JButton("Calculate Total");
        btnTotal.setBounds(350, 170, 150, 35);
        btnTotal.addActionListener(this);
        add(btnTotal);

        // Table
        model = new DefaultTableModel();
        model.setColumnIdentifiers(new String[]{
                "Item", "Unit Price", "Quantity", "Price"
        });

        table = new JTable(model);

        JScrollPane pane = new JScrollPane(table);
        pane.setBounds(50, 230, 580, 150);
        add(pane);

        // Total Label
        lblTotal = new JLabel("Total: Php 0.0");
        lblTotal.setBounds(50, 400, 200, 30);
        add(lblTotal);
    }

    @Override
    public void actionPerformed(ActionEvent e) {

        // Add Item
        if (e.getSource() == btnAdd) {

            int index = cmbItems.getSelectedIndex();
            String item = items[index];
            double unitPrice = prices[index];
            int qty = (Integer) spnQty.getValue();

            double totalPrice = unitPrice * qty;

            model.addRow(new Object[]{
                    item,
                    unitPrice,
                    qty,
                    totalPrice
            });
        }

        // Remove Item
        if (e.getSource() == btnRemove) {

            int row = table.getSelectedRow();

            if (row != -1) {
                model.removeRow(row);
            } else {
                JOptionPane.showMessageDialog(null,
                        "Please select a row to remove.");
            }
        }

        // Calculate Total
        if (e.getSource() == btnTotal) {

            double total = 0;

            for (int i = 0; i < model.getRowCount(); i++) {

                double price = Double.parseDouble(
                        model.getValueAt(i, 3).toString());

                total += price;
            }

            lblTotal.setText("Total: Php " + total);
        }
    }

    public static void main(String[] args) {
        new activity3();
    }
}