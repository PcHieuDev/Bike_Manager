export function validateProduct(product) {
    const requiredFields = ["name", "price", "category_id", "brand_id", "image"];
    const missingFields = requiredFields.some((field) => !product[field]);
    const invalidCharacters = [";", "!", "(", ")", "[", "]", "{", "}", "<", ">", "|", "&", "#", "%", "$", "@", "*", "+", "="];
    const nameContainsInvalidCharacters = invalidCharacters.some((char) => product.name.includes(char));
    const nameIsOnlyNumbers = /^\d+$/.test(product.name);
    if (
        missingFields ||
        isNaN(product.price) ||
        product.price <= 0 ||
        product.price > 1000000 ||
        product.price < 10000 ||
        (product.note && product.note.length > 500) ||
        product.name.length <= 10 ||
        nameContainsInvalidCharacters ||
        nameIsOnlyNumbers
    ) {
        return false;
    }
    return true;
}