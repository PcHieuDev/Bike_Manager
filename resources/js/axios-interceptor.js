import axios from "axios";
import { BASE_URL } from "./configUrl.js";
import { toast } from "vue3-toastify";

const apiClient = axios.create({
  baseURL: BASE_URL,
});

// Thêm response interceptor để xử lý lỗi từ các yêu cầu API
apiClient.interceptors.response.use(
  (response) => {
    // Nếu response không phải lỗi, chỉ cần trả về response
    return response;
  },
  (error) => {
    // Nếu có lỗi, xử lý lỗi
    if (error.response) {
      // Lỗi từ server (có mã trạng thái và dữ liệu phản hồi)
      if (error.response.status === 500) {
        // Xử lý lỗi 500 (Internal Server Error) ở đây
        toast.error("Internal Server Error: " + error.response.data);
      } else if (error.response.status === 404) {
        // Xử lý lỗi 404 (Not Found) ở đây
        toast.error("Not Found: " + error.response.data);
      } else if (error.response.status === 401) {
        // Xử lý lỗi 401 (Unauthorized) ở đây
        toast.error("Unauthorized: " + error.response.data);
      } else {
        // Xử lý các mã lỗi khác nếu cần
        toast.error("Error: " + error.response.data);
      }
    } else if (error.request) {
      // Lỗi không có phản hồi từ server
      toast.error("No response received: " + error.request);
    } else {
      // Lỗi khác không phải từ server hoặc từ yêu cầu
      toast.error("Error: " + error.message);
    }
    // Trả về một Promise bị reject với lỗi đã xử lý
    return Promise.reject(error);
  }
);

export default apiClient;
