<?php
require_once '../Controller/UserController.php';

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $userController = new UserController();
    $users = $userController->searchUsers($query);

    if (count($users) > 0) {
        echo '<table class="min-w-full bg-white shadow-md rounded-lg border-collapse table-auto">';
        echo '<thead>
                <tr class="text-left bg-gray-200 font-bold text-gray-600 uppercase text-sm">
                    <th class="py-2 px-4">No</th>
                    <th class="py-2 px-4">Nama Lengkap</th>
                    <th class="py-2 px-4">NIS</th>
                    <th class="py-2 px-4">NISN</th>
                    <th class="py-2 px-4">Kelas</th>
                    <th class="py-2 px-4">No Whatsapp</th>
                    <th class="py-2 px-4">No Kartu</th>
                    <th class="py-2 px-4">Roles</th>
                    <th class="py-2 px-4">Aksi</th>
                </tr>
              </thead>';
        echo '<tbody>';
        foreach ($users as $i => $user) {
            echo '<tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-2 px-4 text-left whitespace-nowrap">' . ($i + 1) . '</td>
                    <td class="py-2 px-4 text-left">' . $user['nama_lengkap'] . '</td>
                    <td class="py-2 px-4 text-left">' . $user['nis'] . '</td>
                    <td class="py-2 px-4 text-left">' . $user['nisn'] . '</td>
                    <td class="py-2 px-4 text-left">' . $user['kelas'] . '</td>
                    <td class="py-2 px-4 text-left">' . $user['no_whatsapp'] . '</td>
                    <td class="py-2 px-4 text-left">' . $user['no_kartu'] . '</td>
                    <td class="py-2 px-4 text-left">' . $user['roles'] . '</td>
                    <td class="py-2 px-4 text-left">
                        <a href="edit.php?id=' . $user['id_user'] . '" class="text-blue-600">Edit</a>
                        <button onclick="confirmDelete(' . $user['id_user'] . ')" class="text-red-700 ml-2">Delete</button>
                    </td>
                  </tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p class="text-center text-gray-500">No results found.</p>';
    }
}
?>
