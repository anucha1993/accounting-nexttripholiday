{{-- ไฟล์นี้สามารถใช้เป็น template สำหรับ include ในอนาคต --}}
{{-- quotation-form-shared.blade.php --}}

{{-- Tour Search Functionality --}}
<script>
// Tour search AJAX
$(document).ready(function() {
    $('#tourSearch').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            const searchTerm = $(this).val();
            // AJAX call to search tours
            // Implementation needed
        }
    });
    
    // Customer search AJAX  
    $('#customerSearch').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            const searchTerm = $(this).val();
            // AJAX call to search customers
            // Implementation needed
        }
    });
});
</script>

{{-- Shared Validation Script --}}
<script>
function validateQuotationForm() {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    let errors = [];
    
    // Date validations
    const fields = [
        { selector: '#submitDatepickerQuoteDate', name: 'วันที่เสนอราคา' },
        { selector: '#submitDatepicker', name: 'วันที่จองแพคเกจ' },
        { selector: '#date-start', name: 'วันออกเดินทาง' },
        { selector: '#date-end', name: 'วันเดินทางกลับ' }
    ];
    
    fields.forEach(field => {
        const dateVal = $(field.selector).val();
        if (dateVal) {
            const date = new Date(dateVal);
            if (date < today) {
                errors.push(`❌ ${field.name}ต้องเป็นวันปัจจุบันหรืออนาคต`);
            }
        }
    });
    
    // End date must be after start date
    const startDate = $('#date-start').val();
    const endDate = $('#date-end').val();
    if (startDate && endDate && new Date(endDate) <= new Date(startDate)) {
        errors.push("❌ วันเดินทางกลับต้องมากกว่าวันออกเดินทาง");
    }
    
    return errors;
}
</script>
