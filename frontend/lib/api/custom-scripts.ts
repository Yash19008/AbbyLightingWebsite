import { API_URL } from '@/lib/config';

export interface CustomScriptsData {
  enabled: boolean;
  header_scripts: string;
  footer_scripts: string;
}

export async function fetchCustomScripts(): Promise<CustomScriptsData> {
  try {
    const res = await fetch(`${API_URL}/custom-scripts`, {
      next: { revalidate: 60 },
    });

    if (!res.ok) {
      return { enabled: false, header_scripts: '', footer_scripts: '' };
    }

    const json = await res.json();
    if (json.success && json.data) {
      return json.data;
    }

    return { enabled: false, header_scripts: '', footer_scripts: '' };
  } catch (error) {
    console.error('Error fetching custom scripts:', error);
    return { enabled: false, header_scripts: '', footer_scripts: '' };
  }
}
