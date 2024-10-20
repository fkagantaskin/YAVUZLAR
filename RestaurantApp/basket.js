// basket.js

// Sepetteki öğelerin miktarını güncellemek için bir fonksiyon
function updateQuantity(foodId, quantity) {
    fetch('update_quantity.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ food_id: foodId, quantity: quantity }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Miktar başarıyla güncellendi.');
                location.reload();
            } else {
                alert('Miktar güncellenirken bir hata oluştu.');
            }
        })
        .catch(error => console.error('Hata:', error));
}

// Sepetten öğe kaldırma fonksiyonu
function removeItem(foodId) {
    if (confirm('Bu öğeyi sepetten kaldırmak istediğinize emin misiniz?')) {
        fetch('remove_from_basket.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ food_id: foodId }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Öğe sepetten kaldırıldı.');
                    location.reload();
                } else {
                    alert('Öğe kaldırılırken bir hata oluştu.');
                }
            })
            .catch(error => console.error('Hata:', error));
    }
}
