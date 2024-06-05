export function validateProduct(product) {
    const requiredFields = ["name", "price", "category_id", "brand_id", "image"];
    const missingFields = requiredFields.some((field) => !product[field]);
    if (
      missingFields ||
      isNaN(product.price) ||
      product.price <= 0 ||
      product.price > 1000000000 ||
      product.price < 10000 ||
      (product.note && product.note.length > 500)
    ) {
      return false;
    }
    return true;
  }