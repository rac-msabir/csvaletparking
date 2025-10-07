const getTicketPrintTemplate = (ticketNumber, cmds) => `
<!DOCTYPE html>
<html>
  <head>
    <title>POS Print - Ticket ${escapeHtml(ticketNumber)}</title>
    <script>
      // Auto-print when loaded
      window.onload = function() {
        const cmds = ${JSON.stringify(cmds)};
        const blob = new Blob([cmds], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
        
        iframe.onload = function() {
          try {
            iframe.contentWindow.print();
            setTimeout(() => window.close(), 0);
          } catch (e) {
            console.error('Print failed:', e);
            alert('Printing failed. Please check your printer settings.');
          }
        };
        
        iframe.src = url;
      };
    <\/script>
  </head>
  <body>
    <p>If printing doesn't start automatically, please check your printer settings.</p>
  </body>
</html>`;

function escapeHtml(unsafe) {
  return unsafe
    .toString()
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

export default getTicketPrintTemplate;
