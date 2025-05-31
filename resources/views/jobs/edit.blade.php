<x-app-layout>
    <div class="max-w-3xl mx-auto mt-10">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Modifica offerta di lavoro</h1>

        <form action="{{ route('jobs.update', $job) }}" method="POST" enctype="multipart/form-data"
              class="space-y-6 bg-white p-6 rounded-lg shadow">
            @csrf
            @method('PUT')

            <x-job-offert.form 
                :jobOffert="$job ?? null"
                :skills="$skills ?? []"
                :benefits="$benefits ?? []"
            />


            <div class="flex justify-end">
                <button type="submit"
                        class="bg-yellow-600 text-white px-6 py-2 rounded-md hover:bg-yellow-700 transition">
                    Salva modifiche
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
