<script setup>
import { ref, computed, onMounted } from 'vue';
import { useReviewStore } from '@/stores/review';
import AdminLayout from '@/components/AdminLayout.vue';

const reviews = ref([]);
const reviewStore = useReviewStore();
const searchInput = ref('');
const searchQuery = ref('');
const selectedRatings = ref('');
const currentPage = ref(1);
const itemsPerPage = 10;

const performSearch = () => {
  searchQuery.value = searchInput.value.trim();
  currentPage.value = 1;
};

const filteredReviews = computed(() => {
  const search = searchQuery.value.toLowerCase();
  const selectedRating = selectedRatings.value;
  const filtered = reviews.value.filter((review) => {
    const matchesSearch =
      review.resource.toLowerCase().includes(search) ||
      review.resource_ref.toLowerCase().includes(search) ||
      review.id.toString().includes(search);

    // Filter by selected rating if a rating is chosen
    const matchesRating = !selectedRating || review.rate === Number(selectedRating);

    return matchesSearch && matchesRating;
  });

  // Pagination logic
  const startIndex = (currentPage.value - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  return filtered.slice(startIndex, endIndex);
});

const totalPages = computed(() => {
  const search = searchQuery.value.toLowerCase();
  const selectedRating = selectedRatings.value;
  const totalFiltered = reviews.value.filter((review) => {
    const matchesSearch =
      review.resource.toLowerCase().includes(search) ||
      review.resource_ref.toLowerCase().includes(search) ||
      review.id.toString().includes(search);

    // Filter by selected rating if a rating is chosen
    const matchesRating = !selectedRating || review.rate === Number(selectedRating);

    return matchesSearch && matchesRating;
  });
  return Math.ceil(totalFiltered.length / itemsPerPage);
});


const renderReviews = async () => {
  const response = await reviewStore.getAllReviewAdmin();
  console.log(response);
  reviews.value = response.data;
};

// Pagination controls
const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value += 1;
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value -= 1;
  }
};

onMounted(() => {
  renderReviews();
});
</script>

<template>
  <AdminLayout>
    <div class="mx-auto mt-10">
      <!-- Add Order & Search Button -->
      <div class="mb-4 mt-7 flex flex-wrap gap-4 justify-between">
        <!-- Filter Tabs -->
        <div class="w-full lg:w-1/6">
          <select v-model="selectedRatings" class="select select-bordered w-full" @change="renderReviews">
            <option value="">All Ratings</option>
            <option value="5">5 stars</option>
            <option value="4">4 stars</option>
            <option value="3">3 stars</option>
            <option value="2">2 stars</option>
            <option value="1">1 star</option>
          </select>
        </div>

        <!-- Search -->
        <div class="flex items-center w-full lg:w-1/4">
          <input v-model="searchInput" type="text" placeholder="Search..." class="px-4 py-3 rounded w-full" />
          <button @click="performSearch" class="btn btn-square rounded-l primary-btn-bg text-white">
            <svg class="w-[24px] h-[24px] text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
              height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="w-full">
        <table class="w-full table-auto bg-white text-left border-collapse">
          <thead>
            <tr class="border-b border-gray-200 bg-gray-50 text-sm md:text-base">
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">ID</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Resource</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Reference</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Details</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Rate</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Comment</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in filteredReviews" :key="user.id" class="hover:bg-gray-50">
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ user.id }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ user.resource }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ user.resource_ref }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ user.details }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center flex justify-center items-center">
                <span v-for="rate in user.rate">
                  <svg class="w-[24px] h-[24px] text-yellow-500 -mt-1" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                  </svg>
                </span>
              </td>
              <td class="py-4 px-4 text-center">{{ user.comment }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-4 flex justify-between">
        <button @click="prevPage" :disabled="currentPage === 1"
          class="btn px-4 py-2 bg-gray-200 rounded">Previous</button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages"
          class="btn px-4 py-2 bg-gray-200 rounded">Next</button>
      </div>
    </div>
  </AdminLayout>
</template>